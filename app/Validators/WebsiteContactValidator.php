<?php

namespace Apithy\Validators;

/**
 * Class CourseValidator
 * @package Apithy\Validators
 */
class WebsiteContactValidator
{
    /**
     * Registration rules.
     *
     * @param string $type
     * @return array
     */
    public static function storeRules()
    {
        $rules = [
            'email' => 'required|email',
            'name' => 'required',
        ];

        return $rules;
    }
}
