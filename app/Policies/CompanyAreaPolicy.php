<?php

namespace Apithy\Policies;

use Apithy\CompanyArea;
use Apithy\User;
use Apithy\Utilities\Model\Policy;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyAreaPolicy
 * @package Apithy\Policies
 */
class CompanyAreaPolicy extends Policy
{
    /**
     * Resource class name for auto loading permissions.
     *
     * @var string
     */
    protected $resource = CompanyArea::class;

    /**
     * Determine whether the user can view the companyArea.
     *
     * @param  \Apithy\User $user
     * @param Model $model
     * @return mixed
     */
    public function view(User $user, Model $model)
    {
        return $user->isSuper() ||
            ($user->isAdmin() && $user->belongsToCompany($model)) ||
            $this->isAllowedFor($user, 'view');
    }

    /**
     * Determine whether the user can create companyAreas.
     *
     * @param  \Apithy\User $user
     * @param Model|null $model
     * @return mixed
     */
    public function create(User $user, Model $model = null)
    {
        return $user->isSuper() ||
            ($user->isAdmin() && $user->belongsToCompany($model)) ||
            $this->isAllowedFor($user, 'create');
    }

    /**
     * Determine whether the user can update the companyArea.
     *
     * @param  \Apithy\User $user
     * @param Model $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return $user->isSuper() ||
            ($user->isAdmin() && $user->belongsToCompany($model)) ||
            $this->isAllowedFor($user, 'update');
    }

    /**
     * Determine whether the user can delete the companyArea.
     *
     * @param  \Apithy\User $user
     * @param Model|null $model
     * @return mixed
     */
    public function delete(User $user, Model $model = null)
    {
/*
        if ($companyArea->hasUsers()) {
            return false;
        }
*/
        return $user->isSuper() ||
            ($user->isAdmin() && $user->belongsToCompany($model)) ||
            $this->isAllowedFor($user, 'delete');
    }

    /**
     * Determine whether the user can feth the company areas.
     *
     * @param User $user
     * @param Model|null $model
     * @return mixed
     */
    public function fetch(User $user, Model $model = null)
    {
        return $user->isSuper() ||
            ($user->isAdmin() && $user->belongsToCompany($model)) ||
            $this->isAllowedFor($user, 'fetch');
    }

    /**
     * Determine whether the user can show the menu item.
     *
     * @param  \Apithy\User  $auth
     * @return mixed
     */

    public function showResourceMenu(User $auth)
    {
        return $auth->isSuper() || $auth->isAdmin() || $this->isAllowedFor($auth, 'fetch');
    }
}
