<?php

namespace Apithy\Payments;

use Apithy\Payments\Conekta\Billable as BillableContract;
use Illuminate\Support\Facades\Config;

class EloquentBillableRepository implements BillableRepositoryInterface
{
    /**
     * Find a BillableInterface implementation by Conekta ID.
     *
     * @param string $conektaId
     *
     * @return \Apithy\Payments\BillableInterface
     */
    public function find($conektaId)
    {
        $model = $this->createPaymentModel(Config::get('services.payment.model'));

        return $model->where($model->getConektaIdName(), $conektaId)->first();
    }

    /**
     * Create a new instance of the Auth model.
     *
     * @param string $model
     *
     * @return \Apithy\Payments\BillableInterface
     */
    protected function createPaymentModel($class)
    {
        $model = new $class();

        if (!$model instanceof BillableContract) {
            throw new \InvalidArgumentException('Model does not implement Billable.');
        }

        return $model;
    }
}
