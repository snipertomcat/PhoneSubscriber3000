<?php

namespace Apithy\Validators;

use Apithy\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class UserValidator
 * @package Apithy\Validators
 */
class UserValidator
{
    /**
     * Registration rules.
     *
     * @param string $type
     * @param Request $request
     * @return array
     */
    public static function registerRules($type, Request $request)
    {
        $rules = [
            'lms' => [
                'user' => 'required',
                'password' => 'required',
                'phone' => 'nullable|digits_between:8,16|unique:users'
            ],
            'invitation' => [
                'invitation_code' => 'required',
                // 'email' => 'required|email|unique:users,access|unique:users,email',
                'name' => 'required',
                'surname' => 'required',
                'password' => 'required|confirmed',
                // 'phone' => 'nullable|digits_between:8,16|unique:users'
            ],
            'admin' => [
                'email' => 'required|max:255|email|unique:users,access|unique:users,email',
                'name' => 'required|max:255',
                'surname' => 'required|max:255',
                'role_id' => 'required|numeric',
                'phone' => 'nullable|digits_between:8,16|unique:users'
            ],
            'on_site' => [
                'email' => 'required|email|unique:users,access|unique:users,email',
                'name' => 'required',
                'surname' => 'required',
                'password' => 'required|confirmed',
                'phone' => 'nullable|digits_between:8,16|unique:users'
            ],
            'on_site_company' => [
                'email' => 'nullable|email|unique:users,access|unique:users,email',
                'name' => 'required',
                'surname' => 'required',
                'password' => 'required|confirmed',
                'phone' => 'nullable|digits_between:8,16|unique:users,access|unique:users,phone'
            ],
            'import' => [
                'email' => 'email|unique:users,access|unique:users,email',
                'name' => 'required',
                'surname' => 'required',
                'phone' => 'nullable|digits_between:8,16|unique:users'
            ]

        ];

        if ($type === 'invitation') {
            if ($request->input('by_phone', 0)) {
                $rules['invitation']['phone'] = 'required|digits_between:8,16|unique:users';
            } else {
                $rules['invitation']['phone'] = 'nullable|digits_between:8,16|unique:users';
                $rules['invitation']['email'] = 'required|email|unique:users,access|unique:users,email';
            }
        }

        return $rules[$type];
    }

    /**
     * Administration update rules.
     *
     * @param \Apithy\User $auth
     * @param \Apithy\User $user
     * @return array
     */
    public static function adminUpdateRules(User $auth, User $user)
    {
        $rules = [
            'email' => sprintf('required|email|max:255|unique:users,email,%s', $user->id),
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'phone' => [
                'nullable',
                'digits_between:8,16',
                Rule::unique('users')->ignore($user->id)
            ],
            //'ext' => 'digits_between:1,16',
            //'mobile' => 'digits_between:8,16',
            //'skype' => 'max:255',
        ];

        /*
        if ($auth->can('updateCompany', $user)) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        */

        if ($auth->can('updateRole', $user)) {
            $rules['role'] = 'required|exists:roles,id,super,false';
        }

        return $rules;
    }

    /**
     * Reset password rules.
     *
     * @return array
     */
    public static function resetPasswordRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ];
    }

    /**
     * Login rules depending on type.
     *
     * @return array
     */
    public static function loginRules()
    {
        return [
            'access' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Security password update rules.
     *
     * @return array
     */
    public static function securityUpdateRules()
    {
        return [
            'password_current' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ];
    }

    /**
     * Security password update rules.
     *
     * @return array
     */

    public static function securityNewUserUpdateRules()
    {
        return [
            'new_password' => 'required|min:6|confirmed',
        ];
    }

    /**
     * Profile avatar update rules.
     *
     * @return array
     */
    public static function avatarRules()
    {
        return [
            'avatar' => [
                'required',
                'image',
                Rule::dimensions()
                    ->minWidth(300)
                    ->minHeight(300)
                    ->maxWidth(4000)
                    ->maxHeight(4000),
            ],
        ];
    }

    /**
     * Available registration types.
     *
     * @return array
     */
    public static function registerTypes()
    {
        return ['lms', 'invitation','admin','on_site'];
    }

    /**
     * Profile date update rules
     *
     * @param $request
     * @return array
     */
    public static function profileUpdateRules($request)
    {
        return [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'gender' => 'required',
            'phone' => [
                'nullable',
                'digits_between:8,16',
                Rule::unique('users')->ignore($request->user()->id)
            ],
        ];
    }

    public static function mailValidation()
    {
        return [
            'email' => 'required|email|unique:users,access|unique:users,email',
        ];
    }

    public static function personalCodeValidation()
    {
        return [
            'personal_code' => 'unique:users,personal_code',
        ];
    }
}
