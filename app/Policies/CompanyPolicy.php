<?php

namespace Apithy\Policies;

use Apithy\Company;
use Apithy\Role;
use Apithy\User;
use Apithy\Utilities\Model\Policy;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyPolicy
 * @package Apithy\Policies
 */
class CompanyPolicy extends Policy
{
    /**
     * Resource class name for auto loading permissions.
     *
     * @var string
     */
    protected $resource = Company::class;

    /**
     * Determine whether the user can view the company.
     *
     * @param  \Apithy\User  $user
     * @param  \Apithy\Company  $company
     * @return mixed
     */
    public function view(User $user, Model $company)
    {
        return $user->isSuper() ||
            ($user->isAdmin() && $user->belongsToCompany($company)) ||
            $this->isAllowedFor($user, 'view');
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \Apithy\User  $user
     * @return mixed
     */

    public function create(User $user)
    {
        return $user->isSuper() || $this->isAllowedFor($user, 'create');
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param  \Apithy\User  $user
     * @param  \Apithy\Company  $company
     * @return mixed
     */
    public function update(User $user, Model $company)
    {
        return $user->isSuper() || $user->isAdmin() || $this->isAllowedFor($user, 'update');
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param  \Apithy\User  $user
     * @param  \Apithy\Company  $company
     * @return mixed
     */

    public function delete(User $user, Model $company)
    {
        if ($company->hasUsers()) {
            return false;
        }

        return $user->isSuper() || $this->isAllowedFor($user, 'delete');
    }


    /**
     * Determine whether the user can feth the companies.
     *
     * @param  \Apithy\User  $auth
     * @return mixed
     */

    public function fetch(User $auth)
    {
        return $auth->isSuper() || $this->isAllowedFor($auth, 'fetch');
    }

    public function updateAllowedDomain(User $user)
    {
        if ($user->isSuper()) {
            return true;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return true;
    }
}
