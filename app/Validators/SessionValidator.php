<?php

namespace Apithy\Validators;

/**
 * Class CourseValidator
 * @package Apithy\Validators
 */
class SessionValidator
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
            'title' => 'required|unique:experiences',
            'summary' => 'required',
            'visibility' => 'required',
        ];

        return $rules;
    }

    public static function updateRules()
    {
        $rules = [
            'summary' => 'required',
            'visibility' => 'required',
        ];

        return $rules;
    }
}
