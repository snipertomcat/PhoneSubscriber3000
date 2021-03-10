<?php

namespace Apithy\Payments\Conekta;

use Apithy\Payments\Conekta\Customer as Conekta_Customer;
use Apithy\Payments\Contracts\Billable as BillableContract;
use Carbon\Carbon;
use Conekta\ApiError as Conekta_Error;
use Conekta\Charge as Conekta_Charge;
use Conekta\Conekta;
use Conekta\Order as Conekta_Order;
use InvalidArgumentException;

class ConektaGateway
{
    /**
     * The billable instance.
     *
     * @var \Apithy\Payments\Conekta\Billable
     */
    protected $billable;

    /**
     * The name of the plan.
     *
     * @var string
     */
    protected $plan;

    /**
     * The trial end date that should be used when updating.
     *
     * @var \Carbon\Carbon
     */
    protected $trialEnd;

    /**
     * Indicates if the trial should be immediately cancelled for the operation.
     *
     * @var bool
     */
    protected $skipTrial = false;

    /**
     * Create a new Conekta gateway instance.
     *
     * @param \Apithy\Payments\Conekta\Billable $billable
     * @param string|null $plan
     *
     * @return void
     */
    public function __construct(BillableContract $billable, $plan = null)
    {
        $this->plan = $plan;
        $this->billable = $billable;

        Conekta::setApiKey($billable->getConektaKey());
        Conekta::setLocale('es');
    }

    /**
     * Make a "one off" charge on the customer for the given amount.
     *
     * @param int $amount
     * @param array $options
     *
     * @return bool|mixed
     */
    public function charge($amount, array $options = [])
    {
        $options = array_merge([
            'currency' => 'mxn',
        ], $options);

        $options['amount'] = $amount;

        if (!array_key_exists('card', $options) && $this->billable->hasConektaId()) {
            $options['card'] = $this->billable->getConektaId();
        }

        if (!array_key_exists('card', $options)) {
            throw new InvalidArgumentException('No payment source provided.');
        }

        try {
            $response = Conekta_Charge::create($options);
        } catch (Conekta_Error $e) {
            return false;
        }

        return $response;
    }

    /**
     * Make a "one off" charge on the customer for the given amount.
     *
     * @param int $amount
     * @param array $options
     *
     * @return bool|mixed
     */
    public function createOrder($experiences)
    {
        if (!$this->billable->hasConektaId()) {
            throw new InvalidArgumentException('No payment source provided.');
        }

        $order = $this->generateOrderData($experiences, $this->billable->getConektaId());

        try {
            $response = Conekta_Order::create($order);
        } catch (Conekta_Error $e) {
            throw $e;
        }

        return $response;
    }

    /**
     * Subscribe to the plan for the first time.
     *
     * @param string $token
     * @param array $properties
     * @param object|null $customer
     *
     * @return void
     */
    public function create($token, array $properties = [], $customer = null)
    {
        $freshCustomer = false;

        if (!$customer) {
            $customer = $this->createConektaCustomer($token, $properties);

            $freshCustomer = true;
        } elseif (!is_null($token)) {
            $this->updateCard($token);
        }

        $this->billable->setConektaSubscription(
            $customer->updateSubscription($this->buildPayload())->id
        );

        $customer = $this->getConektaCustomer($customer->id);

        if ($freshCustomer && $trialEnd = $this->getTrialEndForCustomer($customer)) {
            $this->billable->setTrialEndDate($trialEnd);
        }

        $this->updateLocalConektaData($customer);
    }

    /**
     * Build the payload for a subscription create / update.
     *
     * @return array
     */
    protected function buildPayload()
    {
        $payload = ['plan' => $this->plan];

        if ($trialEnd = $this->getTrialEndForUpdate()) {
            $payload['trial_end'] = $trialEnd;
        }

        return $payload;
    }

    /**
     * Swap the billable entity to a new plan.
     *
     * @return void
     */
    public function swap()
    {
        $customer = $this->getConektaCustomer();

        // If no specific trial end date has been set, the default behavior should be
        // to maintain the current trial state, whether that is "active" or to run
        // the swap out with the exact number of days left on this current plan.
        if (is_null($this->trialEnd)) {
            $this->maintainTrial();
        }

        return $this->create(null, [], $customer);
    }

    /**
     * Resubscribe a customer to a given plan.
     *
     * @param string $token
     *
     * @return void
     */
    public function resume($token = null)
    {
        $this->skipTrial()->create($token, [], $this->getConektaCustomer());

        $this->billable->setTrialEndDate(null)->saveBillableInstance();
    }

    /**
     * Cancel the billable entity's subscription.
     *
     * @return void
     */
    public function cancel($atPeriodEnd = true)
    {
        $customer = $this->getConektaCustomer();

        if ($customer->subscription) {
            if ($atPeriodEnd) {
                $this->billable->setSubscriptionEndDate(
                    Carbon::createFromTimestamp($this->getSubscriptionEndTimestamp($customer))
                );
            }

            $customer->cancelSubscription(['at_period_end' => $atPeriodEnd]);
        }

        if ($atPeriodEnd) {
            $this->billable->setConektaIsActive(false)->saveBillableInstance();
        } else {
            $this->billable->setSubscriptionEndDate(Carbon::now());

            $this->billable->deactivateConekta()->saveBillableInstance();
        }
    }

    /**
     * Extend a subscription trial end datetime.
     *
     * @param \DateTime $trialEnd
     *
     * @return void
     */
    public function extendTrial(\DateTime $trialEnd)
    {
        $customer = $this->getConektaCustomer();

        if ($customer->subscription) {
            $customer->updateSubscription(['trial_end' => $trialEnd->format(DateTime::ISO8601)]);

            $this->billable->setTrialEndDate($trialEnd)->saveBillableInstance();
        }
    }

    /**
     * Cancel the billable entity's subscription at the end of the period.
     *
     * @return void
     */
    public function cancelAtEndOfPeriod()
    {
        return $this->cancel(true);
    }

    /**
     * Cancel the billable entity's subscription immediately.
     *
     * @return void
     */
    public function cancelNow()
    {
        return $this->cancel(false);
    }

    /**
     * Get the subscription end timestamp for the customer.
     *
     * @param \Conekta_Customer $customer
     *
     * @return int
     */
    protected function getSubscriptionEndTimestamp($customer)
    {
        if (!is_null($customer->subscription->trial_end) && $customer->subscription->trial_end > time()) {
            return $customer->subscription->trial_end;
        } else {
            return $customer->subscription->billing_cycle_end;
        }
    }

    /**
     * Get the current subscription period's end date.
     *
     * @return \Carbon\Carbon
     */
    public function getSubscriptionEndDate()
    {
        $customer = $this->getConektaCustomer();

        return Carbon::createFromTimestamp($this->getSubscriptionEndTimestamp($customer));
    }

    /**
     * Update the credit card attached to the entity.
     *
     * @param string $token
     *
     * @return void
     */
    public function updateCard($data)
    {
        $customer = $this->getConektaCustomer();

        foreach ($customer->payment_sources as $index => $card) {
            if ($card->id == $this->billable->payment_source_id) {
                $customer->payment_sources[$index]->update($data);
            }
        }


        return $customer;
    }


    /**
     * Update the Default Card attached to the entity.
     *
     * @param string $token
     *
     * @return void
     */
    public function setDefaultCard($card_id)
    {
        $customer = $this->getConektaCustomer();
        $customer->update(['default_card_id' => $card_id]);

        return $customer;
    }


    /**
     * Delete the Card by Id.
     *
     * @param string $token
     *
     * @return void
     */

    public function deleteCard($card_id)
    {
        $customer = $this->getConektaCustomer();
        $customer->deletePaymentSourceById($card_id);

        return true;
    }

    /**
     * Get the plan ID for the billable entity.
     *
     * @return string
     */
    public function planId()
    {
        $customer = $this->getConektaCustomer();

        if (isset($customer->subscription)) {
            return $customer->subscription->plan_id;
        }
    }

    /**
     * Update the local Conekta data in storage.
     *
     * @param \Conekta_Customer $customer
     * @param string|null $plan
     *
     * @return void
     */
    public function updateLocalConektaData($customer, $plan = null)
    {
        $this->billable
            ->setConektaId($customer->id)
            ->setConektaPlan($plan ?: $this->plan)
            ->setLastFourCardDigits($this->getLastFourCardDigits($customer))
            ->setCardType($this->getCardType($customer))
            ->setConektaIsActive(true)
            ->setSubscriptionEndDate(null)
            ->saveBillableInstance();
    }

    /**
     * Create a new Conekta customer instance.
     *
     * @param string $token
     * @param array $properties
     *
     * @return \Conekta_Customer
     */
    public function createConektaCustomer($token, array $properties = [])
    {
        $payment_sources = [
            "payment_sources" => [
                [
                    "type" => "card",
                    "token_id" => $token
                ]
            ]
        ];

        $customer = Conekta_Customer::create(
            array_merge($payment_sources, $properties),
            $this->getConektaKey()
        );

        $this->billable
            ->setConektaId($customer->id)
            ->setLastFourCardDigits($this->getLastFourCardDigits($customer))
            ->setCardType($this->getCardType($customer))
            ->setConektaIsActive(true)
            ->setSubscriptionEndDate(null)
            ->setDefaultSource(true)
            ->setPaymentSourceId($customer->default_payment_source_id)
            ->saveBillableInstance();

        return $this->billable;
    }

    /**
     * Update the credit card attached to the entity.
     *
     * @param string $token
     *
     * @return void
     */
    public function addPaymentSource($customer_id, $token)
    {
        $customer = $this->getConektaCustomer($customer_id);

        $card = $customer->createPaymentSource(['token_id' => $token, 'type' => 'card']);

        $customer->update(['default_payment_source_id' => $card->id]);

        $this->billable
            ->setConektaId($customer->id)
            ->setLastFourCardDigits($this->getLastFourCardDigits($customer))
            ->setCardType($this->getCardType($customer))
            ->setConektaIsActive(true)
            ->setSubscriptionEndDate(null)
            ->setPaymentSourceId($card->id)
            ->saveBillableInstance();


        return $this->billable;
    }

    /**
     * Get the Conekta customer for entity.
     *
     * @return \Conekta_Customer
     */
    public function getConektaCustomer($id = null)
    {
        $customer = Customer::retrieve($id ?: $this->billable->getConektaId());

        return $customer;
    }

    /**
     * Get the last four credit card digits for a customer.
     *
     * @param \Conekta_Customer $customer
     *
     * @return string
     */
    protected function getLastFourCardDigits($customer)
    {
        if (empty($customer->payment_sources[0])) {
            return;
        }

        if ($customer->default_payment_source_id) {
            foreach ($customer->payment_sources as $card) {
                if ($card->id == $customer->default_payment_source_id) {
                    return $card->last4;
                }
            }

            return;
        }

        return $customer->payment_sources[0]->last4;
    }

    /**
     * Get the last four credit card digits for a customer.
     *
     * @param \Conekta_Customer $customer
     *
     * @return string
     */
    protected function getCardType($customer)
    {
        if (empty($customer->payment_sources[0])) {
            return;
        }

        if ($customer->default_payment_source_id) {
            foreach ($customer->payment_sources as $card) {
                if ($card->id == $customer->default_payment_source_id) {
                    return $card->brand;
                }
            }

            return;
        }

        return $customer->payment_sources[0]->brand;
    }

    /**
     * Indicate that no trial should be enforced on the operation.
     *
     * @return \Apithy\Payments\Conekta\ConektaGateway
     */
    public function skipTrial()
    {
        $this->skipTrial = true;

        return $this;
    }

    /**
     * Specify the ending date of the trial.
     *
     * @param \DateTime $trialEnd
     *
     * @return \Apithy\Payments\Conekta\ConektaGateway
     */
    public function trialFor(\DateTime $trialEnd)
    {
        $this->trialEnd = $trialEnd;

        return $this;
    }

    /**
     * Get the current trial end date for subscription change.
     *
     * @return \DateTime
     */
    public function getTrialFor()
    {
        return $this->trialEnd;
    }

    /**
     * Maintain the days left of the current trial (if applicable).
     *
     * @return \Apithy\Payments\Conekta\ConektaGateway
     */
    public function maintainTrial()
    {
        if ($this->billable->readyForBilling()) {
            if (!is_null($trialEnd = $this->getTrialEndForCustomer($this->getConektaCustomer()))) {
                $this->calculateRemainingTrialDays($trialEnd);
            } else {
                $this->skipTrial();
            }
        }

        return $this;
    }

    /**
     * Get the trial end timestamp for a Conekta subscription update.
     *
     * @return int
     */
    protected function getTrialEndForUpdate()
    {
        if ($this->skipTrial) {
            return Carbon::now()->toIso8601String();
        }

        return $this->trialEnd ? $this->trialEnd->toIso8601String() : null;
    }

    /**
     * Get the trial end date for the customer's subscription.
     *
     * @param object $customer
     *
     * @return \Carbon\Carbon|null
     */
    public function getTrialEndForCustomer($customer)
    {
        if (isset($customer->subscription) &&
            $customer->subscription->status == 'in_trial' &&
            isset($customer->subscription->trial_end)) {
            return Carbon::createFromTimestamp($customer->subscription->trial_end);
        }
    }

    /**
     * Calculate the remaining trial days based on the current trial end.
     *
     * @param \Carbon\Carbon $trialEnd
     *
     * @return void
     */
    protected function calculateRemainingTrialDays($trialEnd)
    {
        // If there is still trial left on the current plan, we'll maintain that amount of
        // time on the new plan. If there is no time left on the trial we will force it
        // to skip any trials on this new plan, as this is the most expected actions.
        $diff = Carbon::now()->diffInHours($trialEnd);

        return $diff > 0 ? $this->trialFor(Carbon::now()->addHours($diff)) : $this->skipTrial();
    }

    /**
     * Get the Conekta API key for the instance.
     *
     * @return string
     */
    protected function getConektaKey()
    {
        return $this->billable->getConektaKey();
    }

    /**
     * Get the currency for the billable entity.
     *
     * @return string
     */
    protected function getCurrency()
    {
        return $this->billable->getCurrency();
    }

    protected function generateOrderData($experience_data, $customer_id)
    {
        if (!is_array($experience_data)) {
            $experiences=[];
            $experiences[]=$experience_data;
        } else {
            $experiences=$experience_data;
        }

        $order_data = [
            'currency' => 'MXN',
            'customer_info' => array(
                'customer_id' => $customer_id
            ),
            'line_items' => [],
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
            $lineItems[$index ] =
                [
                    'name' => $experience->title,
                    'description' => $experience->summary,
                    'unit_price' => ($experience->price * 100),
                    'quantity' => $experience->quantity,
                    'sku' => $experience->system_id,
                    'metadata'    =>
                        [
                            'experience_id' => $experience->id,
                            'description' => $experience->summary,
                        ],
                ];

            if (isset($experience->cartAttributes)) {
                $lineItems[$index]['metadata']['quantity'] = $experience->quantity;
                $lineItems[$index]['metadata']['inherit_users']=$experience->cartAttributes->inherit_users;
                $lineItems[$index]['metadata']['corporative_use']=$experience->cartAttributes->corporative_use;
                $lineItems[$index]['metadata']['personal_use']=$experience->cartAttributes->personal_use;
                $lineItems[$index]['metadata']['company_areas']=$experience->cartAttributes->company_areas;
                $lineItems[$index]['metadata']['company_positions']=$experience->cartAttributes->company_positions;
                $lineItems[$index]['metadata']['company_users']=$experience->cartAttributes->company_users;
                $lineItems[$index]['metadata']['new_users']=$experience->cartAttributes->new_users;
                $lineItems[$index]['metadata']['licences_number']=$experience->cartAttributes->licences_number;
                $lineItems[$index]['metadata']['licences_to_buy']=$experience->cartAttributes->licences_to_buy;
                $lineItems[$index]['metadata']['free_licences']=$experience->cartAttributes->free_licences;
                $lineItems[$index]['metadata']['assignation_id'] = $experience->cartAttributes->assignation_id;
            }
        }

        $order_data['line_items'] = $lineItems;


        return $order_data;
    }
}
