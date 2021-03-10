<?php

namespace Apithy\Payments;

interface BillableRepositoryInterface
{
    /**
     * Find a BillableInterface implementation by Conekta ID.
     *
     * @param string $conektaId
     *
     * @return \Apithy\Payments\BillableInterface
     */
    public function find($conektaId);
}
