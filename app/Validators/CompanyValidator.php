<?php

namespace Apithy\Validators;

/**
 * Class CompanyValidator
 * @package Apithy\Validators
 */
class CompanyValidator
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
            'contact_email' => 'email',
        ];
    }

    public static function fromUserRegister()
    {
        return [
            'company*info.name' => 'required|max:255',
            'company*info.short_name' => 'required|max:255',
            'company*info.site_url' => 'url',
            'company*info.contact_email' => 'email',
        ];
    }

    public static function nameValidator()
    {
        return [
            'name' => 'required|max:255|unique:companies,name',
        ];
    }

    public static function rfcValidator()
    {
        return [
            'rfc' => 'required|max:255|unique:companies,rfc',
        ];
    }

    public static function legalNameValidator()
    {
        return [
            'legal_name' => 'required|max:255|unique:companies,legal_name',
        ];
    }

    public static function accountNameValidator()
    {
        return [
            'account_name' => 'required|max:255|unique:companies,account_name',
        ];
    }

    public static function allowedDomainValidator()
    {
        return [
            'valid_domains' => 'required|array',
            'valid_domains.*.domain' => 'required|string'
        ];
    }
}
