<?php

namespace Apithy\Validators;

/**
 * Class InvitationValidator
 * @package Apithy\Validators
 */
class InvitationValidator
{
    /**
     * Registration rules.
     *
     * @return array
     */
    public static function inviteRules()
    {
        return [
            'email' => 'required|email|unique:invitations,contact',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public static function roleRules()
    {
        return [
            'role_id' => 'required|exists:roles,id|in:7,9'
        ];
    }

    public static function emailRule()
    {
        return [
            'email' => 'required|email|unique:invitations,contact|unique:users'
        ];
    }

    public static function phoneRule()
    {
        return [
            'phone' => 'required|digits_between:8,10|unique:invitations,contact|unique:users'
        ];
    }
}
