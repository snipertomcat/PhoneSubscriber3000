<?php

namespace Apithy\Policies;

use Apithy\CompanyPosition;
use Apithy\User;
use Apithy\Utilities\Model\Policy;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PositionPolicy
 * @package Apithy\Policies
 */
class PositionPolicy extends Policy
{

    protected $resource = CompanyPosition::class;

    /**
     * Determine whether the user can view the companyPosition.
     *
     * @param  \Apithy\User $user
     * @param Model $model
     * @return mixed
     */
    public function view(User $user, Model $model)
    {
        // Verify if this user can view this position.
        return $user->isSuper() ||
            (
                $user->isAdmin() &&
                $user->belongsToCompany($model->company)
            ) ||
            $this->isAllowedFor($user, 'view');
    }

    /**
     * Determine whether the user can create companyPositions.
     *
     * @param  \Apithy\User  $user
     * @return mixed
     */
    public function create(User $user, Model $model = null)
    {
        // Verify if this user can create this position.
        return $user->isSuper() ||
            (
                $user->isAdmin() &&
                $user->belongsToCompany($model->company)
            ) ||
            $this->isAllowedFor($user, 'create');
    }

    /**
     * Determine whether the user can update the companyPosition.
     *
     * @param  \Apithy\User $user
     * @param Model $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return $user->isSuper() ||
            (
                $user->isAdmin() &&
                $user->belongsToCompany($model->company)
            ) ||
            $this->isAllowedFor($user, 'update');
    }

    /**
     * Determine whether the user can delete the companyPosition.
     *
     * @param  \Apithy\User $user
     * @param Model $model
     * @return mixed
     */
    public function delete(User $user, Model $model)
    {
        // Verify if this user can view this position.
        return $user->isSuper() ||
            (
                $user->isAdmin() &&
                $user->belongsToCompany($model->company)
            ) ||
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
            (
                $user->isAdmin() &&
                $user->belongsToCompany($model)
            ) ||
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
