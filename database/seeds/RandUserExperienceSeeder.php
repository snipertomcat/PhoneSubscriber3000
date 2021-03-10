<?php

use Apithy\Company;
use Apithy\CompanyBudget;
use Apithy\Experience;
use Apithy\ExperienceLicence;
use Apithy\Purchase;
use Apithy\Transaction;
use Apithy\TransactionDetails;
use Apithy\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RandUserExperienceSeeder extends Seeder
{
    const QUANTITY = 100;
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        try {
            if (!$this->command->confirm('¿Estás seguro de ejecutar este seeder?')) {
                $this->command->line('-----------------------------------------------');
                $this->command->alert('Acción cancelada');
                return;
            }

            $company_id = $this->command->ask('De cual id de compañia se obtendran datos? (default 1)', 1);
            $this->command->line('-----------------------------------------------');

            $this->command->info("Iniciando la transacción con id de compañia {$company_id}");
            $this->command->line('-----------------------------------------------');

            DB::transaction(function () use ($company_id) {
                $company = Company::where('id', $company_id)
                    ->firstOrFail();

                $this->command->info("Los datos se obtienen de la compañia: {$company->name}");
                $this->command->line('-----------------------------------------------');

                $this->command->info("Obteniendo administrador de la compañia (role id 9 o 10)");
                $this->command->line('-----------------------------------------------');

                $admin = $company->users()
                    ->whereHas('roles', function ($query) {
                        $query->where('id', 10)
                            ->orWhere('id', 9);
                    })
                    ->firstOrFail();

                $this->command->info("El administrador actual es: {$admin->full_name}");
                $this->command->line('-----------------------------------------------');

                $this->command->info("Obteniendo estudiantes de la compañia: {$company->name} (role id 7)");
                $this->command->line('-----------------------------------------------');

                $users = $company->users()
                    ->whereHas('roles', function ($query) {
                        $query->where('id', 7);
                    })
                    ->doesntHave('enrollments')
                    ->doesntHave('licences')
                    ->doesntHave('purchases')
                    ->where('status', User::STATUS_ACTIVE)
                    ->limit(self::QUANTITY)
                    ->get();
                $uCount = count($users);

                if ($uCount === 0) {
                    $this->command->warn("No hay alumnos registrados o disponibles, la operación fue cancelada");
                    $this->command->line('-----------------------------------------------');
                    return;
                }

                $this->command->info("Se obtiene un total de {$uCount} alumnos");
                $this->command->line('-----------------------------------------------');

                $this->command->info("Obteniendo experiencias de Apithy");
                $this->command->line('-----------------------------------------------');

                $experiences = Experience::where([
                    ['type', 'adventure'],
                    ['author', 1],
                    ['visibility', 1],
                    ['status', 1]
                ])
                    ->get();
                $eCount = count($experiences);

                if ($eCount === 0) {
                    $this->command->warn("No hay experiencias disponibles, la operación fue cancelada");
                    $this->command->line('-----------------------------------------------');
                    return;
                }

                $this->command->info("Se obtiene un total de {$eCount} experiencias");
                $this->command->line('-----------------------------------------------');

                $this->command->info("Registrando compra de licencias");
                $this->command->line('-----------------------------------------------');

                $order = $this->freeOrder($experiences);

                $order->amount = ($order->amount / 100);

                $transaction = $this->createTransaction($admin, $order);

                $experiences_licences = $this->createTransactionDetails($admin, $transaction, $order, $company);

                $this->command->info("Asignando licencias");
                $this->command->line('-----------------------------------------------');
                $this->setLicenseToUsers($users, $experiences_licences, $admin);

                $this->command->info("Los siguientes usuarios recibieron licencias");
                $this->command->line('-----------------------------------------------');
                foreach ($users as $user) {
                    $this->command->info("Nombre: $user->full_name");
                    $this->command->info("Correo: $user->email");
                    $this->command->line('-----------------------------------------------');
                }

            });
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->command->error("Error en la operación: {$ex->getMessage()} \n {$ex->getTraceAsString()}");
        }
    }

    private function setLicenseToUsers($users, $experiences, $admin)
    {
        $time = Carbon::now()->toDateTimeString();
        foreach ($experiences as $key => &$experience) {
            foreach ($users as &$user) {
                $hasLicense = false;
                foreach ($experience['experience']['licenses'] as &$license) {
                    if ($license->status === ExperienceLicence::STATUS_AVAILABLE && !$hasLicense) {
                        $license->user_id = $user->id;
                        $license->status = ExperienceLicence::STATUS_UNAVAILABLE;
                        $license->saveOrFail();
                        $hasLicense = true;
                        break;
                    }
                }
                if ($hasLicense) {
                    $user->enrollments()->attach($experience['experience']['id'], [
                        'status' => User::USER_ENROLLMENT_STATUS_ENROLLED,
                        'created_at' => $time,
                        'updated_at' => $time
                    ]);
                }
            }
        }
    }

    private function createTransaction($admin, $order)
    {
        $transaction = Transaction::create([
            'user_payment_information_id' => null,
            'user_id' => $admin->id,
            'type' => 'charge',
            'amount' => $order->amount,
            'provider_charge_id' => $order->id,
            'provider_customer_id' => null,
            'provider_payment_source_id' => null,
            'description' => '',
            'status' => 1,
            'created_at' => Carbon::now()
        ]);
        return $transaction;
    }

    private function createTransactionDetails($admin, $transaction, $order, $company)
    {
        $company_charges = 0;
        $licenses = collect();
        foreach ($order->line_items as $key => $line_item) {
            TransactionDetails::create([
                'transaction_id' => $transaction->id,
                'price' => ($line_item->unit_price / 100),
                'status' => 1,
                'created_at' => Carbon::now(),
                'experience_id' => $line_item->metadata->experience_id,
                'description' => $line_item->metadata->description,
                'assignation_id' => null,
                'company_use' => 1,
                'personal_use' => 0,
                'quantity' => $line_item->quantity
            ]);

            $purchase = Purchase::create([
                'item_id' => $line_item->metadata->experience_id,
                'item_type' => 'experience',
                'created_at' => Carbon::now(),
                'user_id' => $admin->id,
                'company_use' => 1,
                'personal_use' => 0,
                'company_areas' => [],
                'company_positions' => [],
                'company_users' => [],
                'new_users' => [],
                'empty_licences' => self::QUANTITY,
                'transaction_id' => $transaction->id
            ]);

            $company_charges +=
                (($line_item->unit_price / 100) * $line_item->metadata->quantity);

            $aux['experience']['id'] = $line_item->metadata->experience_id;

            for ($i = 0; $i < self::QUANTITY; $i++) {
                $el = new ExperienceLicence([
                    'user_id' => null,
                    'buyed_by' => $admin->id,
                    'experience_id' => $line_item->metadata->experience_id,
                    'status' => ExperienceLicence::STATUS_AVAILABLE,
                    'user_purchase_id' => $purchase->id,
                    'created_at' => Carbon::now()
                ]);
                $el->saveOrFail();
                $aux['experience']['licenses'][] = $el;
                $el = null;
            }
            $licenses->push($aux);
            $aux = null;
        }

        $this->companyCharges($admin, $company, $company_charges);

        return $licenses;
    }

    private function companyCharges($admin, $company, $amount)
    {
        if ($company->budget_balance != null) {
            $current_balance = CompanyBudget::where('company_id', $company->id)
                ->get()->last();

            if ($current_balance) {
                $new_balance_row = new CompanyBudget([
                    'company_id' => $company->id,
                    'user_id' => $admin->id,
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

    private function freeOrder($experiences)
    {
        $order_data = [
            'id' => 'free-order-' . time(),
            'currency' => 'MXN',
            'amount' => 0,
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

        foreach ($experiences as $key => $experience) {
            $order_data['amount'] += ($experience->price * 100);
            $lineItems[] = [
                'name' => $experience->title,
                'description' => $experience->summary,
                'unit_price' => ($experience->price * 100),
                'quantity' => self::QUANTITY,
                'sku' => $experience->system_id,
                'metadata' => [
                    'experience_id' => $experience->id,
                    'description' => $experience->summary,
                    'quantity' => self::QUANTITY
                ],
            ];
        }
        $order_data['line_items'] = $lineItems;
        return json_decode(json_encode($order_data), false);
    }
}
