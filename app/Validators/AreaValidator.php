<?php

namespace Apithy\Validators;

/**
 * Class AreaValidator
 * @package Apithy\Validators
 */
class AreaValidator
{
    /**
     * Store rules.
     *
     * @return array
     */
    public static function storeRules()
    {
        return [
            'name' => 'required|max:255',
            'short_name' => 'required|max:255',
            'description' => 'max:255'
        ];
    }
}
