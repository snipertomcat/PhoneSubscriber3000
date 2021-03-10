<?php

namespace Apithy\Validators;

/**
 * Class CourseValidator
 * @package Apithy\Validators
 */
class ExperienceValidator
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
            'company_objectives' => 'required',
            'area_objectives' => 'required',
            'visibility' => 'required',
        ];

        return $rules;
    }

    public static function updateRules()
    {
        $rules = [
            'summary' => 'required',
            'company_objectives' => 'required',
            'area_objectives' => 'required',
            'visibility' => 'required',
        ];

        return $rules;
    }
}
