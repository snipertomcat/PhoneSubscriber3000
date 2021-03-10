<?php

namespace Apithy\Payments\Conekta;

interface PlanInterface
{
    /**
     * Get the Conekta ID for the plan.
     *
     * @return string
     */
    public function getConektaId();
}
