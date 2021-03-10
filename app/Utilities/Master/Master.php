<?php

namespace Apithy\Utilities\Master;

use Apithy\Utilities\Master\Traits\HelperTrait;
use Apithy\Utilities\Master\Traits\ResponseTrait;
use Apithy\Utilities\Master\Traits\StringTrait;

class Master
{
    use StringTrait, ResponseTrait, HelperTrait;

    protected static $instance = null;

    /**
     * Prevent direct object creation
     */
    final private function __construct()
    {
        //
    }

    /**
     * Prevent object cloning
     */
    final private function __clone()
    {
        //
    }

    /**
     * Returns new or existing Singleton instance
     * @return Master
     */
    final public static function getInstance()
    {
        if (null !== static::$instance) {
            return static::$instance;
        }
        static::$instance = new static();
        return static::$instance;
    }

    public static function hasDebug()
    {
        return env('APP_DEBUG', false);
    }
}
