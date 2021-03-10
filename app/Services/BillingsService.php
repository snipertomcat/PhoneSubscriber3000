<?php

namespace Apithy\Services;

use Apithy\CompanyBudget;
use Apithy\Events\InvitationCreated;
use Apithy\Events\InvitationCreatedFromPhone;
use Apithy\Exceptions\NotPaymentSourceException;
use Apithy\ExperienceAccess;
use Apithy\ExperienceLicence;
use Apithy\Invitation;
use Apithy\PaymentInformation;
use Apithy\Purchase;
use Apithy\Role;
use Apithy\Transaction;
use Apithy\TransactionDetails;
use Apithy\User;
use Apithy\Experience;
use Apithy\Utilities\Master\Master;
use Carbon\Carbon;
use Conekta\PaymentSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class EnrollmentService
 * @package Apithy\Services
 */
class BillingsService
{
    const TRACKING_TYPE_XAPI = 'xapi';
    const TRACKING_TYPE_VIDEO = 'video_event';

    const SESSION_STATUS_BLOCKED = 0;
    const SESSION_STATUS_AVAILABLE = 1;
    const SESSION_STATUS_IN_PROGRESS = 2;
    const SESSION_STATUS_FINISHED = 3;
    const SESSION_STATUS_RETRY = 4;
    const SESSION_STATUS_FINISHED_PENDING_ANSWERS = 5;

    const ENROLLMENT_STATUS_ABANDONED = 0;
    const ENROLLMENT_STATUS_ENROLLED = 1;
    const ENROLLMENT_STATUS_FINISHED = 2;
    const ENROLLMENT_STATUS_SUSPENDED = 3;
    const ENROLLMENT_STATUS_EXPIRED = 4;
    const ENROLLMENT_STATUS_EXPULSED = 5;
    const ENROLLMENT_STATUS_ACCESS = 6;
    const ENROLLMENT_STATUS_IN_PROGRESS = 7;

    const XAPI_VERB_ATTEMPTED = 'attempted';
    const XAPI_VERB_COMPLETED = 'completed';
    const XAPI_VERB_ANSWERED = 'answered';
    const XAPI_VERB_INTERACTED = 'interacted';

    const VIDEO_STARTED = 'video_started';
    const VIDEO_SEEKING = 'video_seeking';
    const VIDEO_PAUSED = 'video_paused';
    const VIDEO_FINISHED = 'video_finished';


    public function orderExperiences(User $user, $experiences = [], $payment_source_id = null)
    {
        $transaction = null;
        $payment_source = null;
        $company_charges = 0;

        $is_freeOrder = $this->isFreeOrder($experiences);


        if ($payment_source_id) {
            $payment_source = PaymentInformation::find($payment_source_id);

            if (!$payment_source) {
                $payment_source = $user->getDefaulPaymentSource();
            }
        } else {
            $payment_source = $user->getDefaulPaymentSource();
        }

        if (!$payment_source && !$is_freeOrder) {
            return false;
            //throw new NotPaymentSourceException('No payment source provided.', 400);
        }


        if ($is_freeOrder) {
            //No Conekta Charges
            $order = $this->createFreeOrder($experiences);
        } else {
            $order = $payment_source->createOrder($experiences);
            //print_r($order);
        }

        if ($order) {
            $order->amount = ($order->amount / 100);
            $exception = DB::transaction(
                function () use ($is_freeOrder, $order, $user, $payment_source, $transaction, $company_charges) {
                    try {
                        $transaction = Transaction::create([
                            'user_payment_information_id' => ($payment_source) ? $payment_source->id : null,
                            'user_id' => $user->id,
                            'type' => 'charge',
                            'amount' => $order->amount,
                            'provider_charge_id' => $order->id,
                            'provider_customer_id' => ($payment_source) ? $payment_source->stripe_id : null,
                            'provider_payment_source_id' => ($payment_source)
                                ? $payment_source->payment_source_id
                                : null,
                            'description' => '',
                            'status' => 1,
                            'created_at' => Carbon::now()
                        ]);

                        foreach ($order->order->items as $line_item) {
                            if ($is_freeOrder) {
                                $line_item->metadata = (array) $line_item->metadata;
                            }

                            if (!$line_item->amount && ! isset($line_item->metadata)) {
                                continue;
                            }

                            $transaction_detail_data = [
                                'transaction_id' => $transaction->id,
                                'price' => ($line_item->amount / 100),
                                'status' => 1,
                                'created_at' => Carbon::now(),
                                'experience_id' => $line_item->metadata['experience_id'],
                                'description' => (!$is_freeOrder) ? $line_item['description'] : $line_item->description,
                                'assignation_id' => $line_item->metadata['assignation_id'],
                                'company_use' => ($line_item->metadata['corporative_use']) ? 1 : 0,
                                'personal_use' => ($line_item->metadata['personal_use']) ? 1 : 0,
                                'quantity' => $line_item->quantity
                            ];

                            TransactionDetails::create($transaction_detail_data);

                            $purchase = Purchase::create([
                                'item_id' => $line_item->metadata['experience_id'],
                                'item_type' => 'experience',
                                'created_at' => Carbon::now(),
                                'user_id' => $user->id,
                                'company_use' => ($line_item->metadata['corporative_use']) ? 1 : 0,
                                'personal_use' => ($line_item->metadata['personal_use']) ? 1 : 0,
                                'company_areas' => $line_item->metadata['company_areas'],
                                'company_positions' => $line_item->metadata['company_positions'],
                                'company_users' => $line_item->metadata['company_users'],
                                'new_users' => $line_item->metadata['new_users'],
                                'empty_licences' => $line_item->metadata['licences_number'],
                                'transaction_id' => ($transaction) ? $transaction->id : null
                            ]);

                            if ($line_item->metadata['corporative_use']) {
                                $company_charges +=
                                    (($line_item->amount / 100) * $line_item->quantity);
                            }

                            if ($line_item->metadata['personal_use']) {
                                ExperienceLicence::create([
                                    'user_id' => $user->id,
                                    'buyed_by' => $user->id,
                                    'experience_id' => $line_item->metadata['experience_id'],
                                    'status' => ExperienceLicence::STATUS_UNAVAILABLE,
                                    'user_purchase_id' => $purchase->id,
                                    'created_at' => Carbon::now(),
                                ]);
                            }

                            //Only Licenes creations
                            if ($line_item->metadata['free_licences']) {
                                if ($line_item->metadata['free_licences']) {
                                    for ($x = 0; $x < $line_item->metadata['licences_number']; $x++) {
                                        ExperienceLicence::create([
                                            'user_id' => null,
                                            'buyed_by' => $user->id,
                                            'experience_id' => $line_item->metadata['experience_id'],
                                            'status' => ExperienceLicence::STATUS_AVAILABLE,
                                            'user_purchase_id' => $purchase->id,
                                            'created_at' => Carbon::now(),
                                        ]);
                                    }
                                }
                            } else {
                                $licences_to_buy = $line_item->metadata['licences_to_buy'];
                                $free_licences = $line_item->metadata['licences_number'] - $licences_to_buy;

                                $licence_users = [];


                                if (count($line_item->metadata['inherit_users'])) {
                                    foreach ((Array)$line_item->metadata['inherit_users'] as $user_inherit) {
                                        $licence_users[] = [
                                            'id' => $user_inherit,
                                            'is_apithy_user' => true,
                                            'email' => ''
                                        ];
                                    }
                                }

                                if (count($line_item->metadata['new_users'])) {
                                    foreach ((Array)$line_item->metadata['new_users'] as $new_user) {
                                        $licence_users[] = [
                                            'id' => null,
                                            'is_apithy_user' => false,
                                            'email' => $new_user
                                        ];
                                    }
                                }

                                if ($free_licences) {
                                    $licences_to_assign = $this->getFreeLicences(
                                        $line_item->metadata['experience_id'],
                                        $free_licences
                                    );

                                    $users_to_assign = array_slice(
                                        (Array)$licence_users,
                                        0,
                                        $free_licences
                                    );

                                    $licence_to_create = array_slice(
                                        (Array)$licence_users,
                                        $free_licences
                                    );

                                    foreach ($licences_to_assign as $index => $licence) {
                                        if ($users_to_assign[$index]['is_apithy_user']) {
                                            $licence->user_id = $users_to_assign[$index]['id'];
                                            $licence->status = ExperienceLicence::STATUS_UNAVAILABLE;
                                            $licence->save();
                                        } else {
                                            $licence->email = $users_to_assign[$index]['email'];
                                            $licence->status = ExperienceLicence::STATUS_WAITING_CONFIRMATION;
                                            $licence->save();

                                            $this->createInvitation(
                                                $users_to_assign[$index]['email'],
                                                $line_item->metadata['experience_id']
                                            );
                                        }
                                    }

                                    foreach ($licence_to_create as $user_licence) {
                                        if ($user_licence['is_apithy_user']) {
                                            ExperienceLicence::create([
                                                'user_id' => $user_licence['id'],
                                                'buyed_by' => $user->id,
                                                'experience_id' => $line_item->metadata['experience_id'],
                                                'status' => ExperienceLicence::STATUS_UNAVAILABLE,
                                                'user_purchase_id' => $purchase->id,
                                                'created_at' => Carbon::now(),
                                            ]);
                                        } else {
                                            ExperienceLicence::create([
                                                'email' => $user_licence['email'],
                                                'buyed_by' => $user->id,
                                                'experience_id' => $line_item->metadata['experience_id'],
                                                'status' => ExperienceLicence::STATUS_UNAVAILABLE,
                                                'user_purchase_id' => $purchase->id,
                                                'created_at' => Carbon::now(),
                                            ]);

                                            $this->createInvitation(
                                                $user_licence['email'],
                                                $line_item->metadata['experience_id']
                                            );
                                        }
                                    }
                                } else {
                                    if ($line_item->metadata['corporative_use']
                                        && count($licence_users)) {
                                        foreach ($licence_users as $user_licence) {
                                            if ($user_licence['is_apithy_user']) {
                                                $experienceLicence = ExperienceLicence::create([
                                                    'user_id' => $user_licence['id'],
                                                    'buyed_by' => $user->id,
                                                    'experience_id' => $line_item->metadata['experience_id'],
                                                    'status' => ExperienceLicence::STATUS_UNAVAILABLE,
                                                    'user_purchase_id' => $purchase->id,
                                                    'created_at' => Carbon::now(),
                                                ]);

                                                $experienceLicence
                                                    ->notify(app(\Apithy\Notifications\UserLicenceAssigned::class));
                                            } else {
                                                ExperienceLicence::create([
                                                    'email' => $user_licence['email'],
                                                    'buyed_by' => $user->id,
                                                    'experience_id' => $line_item->metadata['experience_id'],
                                                    'status' => ExperienceLicence::STATUS_WAITING_CONFIRMATION,
                                                    'user_purchase_id' => $purchase->id,
                                                    'created_at' => Carbon::now(),
                                                ]);

                                                $this->createInvitation(
                                                    $user_licence['email'],
                                                    $line_item->metadata['experience_id']
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if ($user->isAdmin()) {
                            $this->updateCompanyBudget($company_charges);
                        }

                        $transaction->notify(app(\Apithy\Notifications\Invoice::class));
                    } catch (Exception $e) {
                        return $e;
                    }
                }
            );

            return is_null($exception) ? true : false;
        }
    }

    protected function getFreeLicences($experience_id, $number)
    {
        $auth = Auth::user();

        return ExperienceLicence::take($number)->where('experience_id', $experience_id)
            ->where('buyed_by', $auth->id)
            ->where('status', ExperienceLicence::STATUS_AVAILABLE)->get();
    }

    protected function createInvitation($email, $experience_id)
    {
        $invitation = Invitation::where('contact', $email)->first();

        if (!$invitation) {
            $invitation = Invitation::create(
                [
                    'code' => md5($email),
                    'user_id' => \Auth::user()->id,
                    'role_id' => Role::STUDENT,
                    'status' => Invitation::INVITATION_PENDING,
                    'contact' => $email,
                    'company_id' => Auth::user()->company()->first()->id,
                    'experience_id' => $experience_id
                ]
            );
        }

        if ($invitation) {
            if (Master::isPhone($invitation->contact)) {
                event(new InvitationCreatedFromPhone($invitation));
            } else {
                event(new InvitationCreated($invitation));
            }
        }
    }

    protected function updateCompanyBudget($amount)
    {
        if ($auth = Auth::user()) {
            $company = $auth->company()->first();
            if ($company) {
                if ($company->budget_balance != null) {
                    $current_balance = CompanyBudget::where('company_id', $company->id)
                        ->get()->last();

                    if ($current_balance) {
                        $new_balance_row = new CompanyBudget([
                            'company_id' => $company->id,
                            'user_id' => $auth->id,
                            'type' => CompanyBudget::BUDGET_GLOBAL,
                            'operation_type' => CompanyBudget::BUDGET_TYPE_OUT,
                            'amount' => $amount,
                            'current_balance' => $current_balance->current_balance - $amount
                        ]);
                        $new_balance_row->save();
                        $company->budget_balance = $new_balance_row->current_balance;
                        $company->save();
                    }
                }
            }
        }
    }

    public function isFreeOrder($experiences)
    {
        foreach ($experiences as $index => $experience) {
            if ($experience->price > 0) {
                return false;
            }
        }

        return true;
    }

    public function createFreeOrder($experience_data)
    {
        if (!is_array($experience_data)) {
            $experiences = [];
            $experiences[] = $experience_data;
        } else {
            $experiences = $experience_data;
        }

        $order_data = [
            'id' => 'free-order-' . time(),
            'currency' => 'MXN',
            'amount' => 0,
            'order' => [],
            'charges' => [
                [
                    'payment_method' => [
                        'type' => 'default'
                    ],
                ]
            ]
        ];

        $lineItems = [];

        foreach ($experiences as $index => $experience) {
            $order_data['amount'] += ($experience->price * 100);
            $lineItems[$index] =
                [
                    'name' => $experience->title,
                    'description' => $experience->summary,
                    'amount' => ($experience->price * 100),
                    'quantity' => $experience->quantity,
                    'sku' => $experience->system_id,
                    'metadata' =>
                        [
                            'experience_id' => $experience->id,
                            'description' => $experience->summary,
                        ],
                ];

            if (isset($experience->cartAttributes)) {
                $lineItems[$index]['metadata']['inherit_users'] = $experience->cartAttributes->inherit_users;
                $lineItems[$index]['metadata']['quantity'] = $experience->quantity;
                $lineItems[$index]['metadata']['corporative_use'] = $experience->cartAttributes->corporative_use;
                $lineItems[$index]['metadata']['personal_use'] = $experience->cartAttributes->personal_use;
                $lineItems[$index]['metadata']['company_areas'] = $experience->cartAttributes->company_areas;
                $lineItems[$index]['metadata']['company_positions'] = $experience->cartAttributes->company_positions;
                $lineItems[$index]['metadata']['company_users'] = $experience->cartAttributes->company_users;
                $lineItems[$index]['metadata']['new_users'] = $experience->cartAttributes->new_users;
                $lineItems[$index]['metadata']['licences_number'] = $experience->cartAttributes->licences_number;
                $lineItems[$index]['metadata']['licences_to_buy'] = $experience->cartAttributes->licences_to_buy;
                $lineItems[$index]['metadata']['free_licences'] = $experience->cartAttributes->free_licences;
                $lineItems[$index]['metadata']['assignation_id'] = $experience->cartAttributes->assignation_id;
            }
        }

        $order_data['order']['items'] = $lineItems;

        return json_decode(json_encode($order_data), false);
    }
}
