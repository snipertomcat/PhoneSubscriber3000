<?php

namespace Apithy\Utilities\Model;

/**
 * Class HashIdTrait
 * @package Apithy\Utilities\Model
 */
trait HashIdTrait
{
    /**
     * Return HasURL path cover.
     *
     * @return string
     */
    public function getSystemIdAttribute()
    {
        $hashids = new \Hashids\Hashids(env('APP_URL_KEY'), 5);
        return $hashids->encode($this->id);
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        $hashids = new \Hashids\Hashids(env('APP_URL_KEY'), 5);

        return $hashids->encode($this->getKey());
    }
}
