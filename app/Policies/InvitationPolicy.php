<?php

namespace Apithy\Policies;

use Apithy\Invitation;
use Apithy\User;
use Apithy\Utilities\Model\Policy;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyPolicy
 * @package Apithy\Policies
 */
class InvitationPolicy extends Policy
{
    /**
     * Resource class name for auto loading permissions.
     *
     * @var string
     */
    protected $resource = Invitation::class;

    /**
     * Determine whether the user can view the company.
     *
     * @param  \Apithy\User  $user
     * @param  \Apithy\Invitation  $company
     * @return mixed
     */
    public function view(User $user, Model $invitation)
    {
        return $user->isSuper() || $user->isAdmin() || $this->isAllowedFor($user, 'view');
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \Apithy\User  $user
     * @return mixed
     */


    public function create(User $user)
    {
        return $user->isSuper() || $user->isAdmin() || $this->isAllowedFor($user, 'create');
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param  \Apithy\User  $user
     * @param  \Apithy\Invitation  $nvitation
     * @return mixed
     */
    public function delete(User $user, Model $invitation)
    {
        return $user->isSuper() || $user->isAdmin() || $this->isAllowedFor($user, 'delete');
    }

    /**
     * Determine whether the user can fetch invitations.
     *
     * @param  \Apithy\User  $auth
     * @return mixed
     */

    public function fetch(User $auth)
    {
        return $auth->isSuper() || $auth->isAdmin();
    }
    

    /**
     * Determine if the authenticated user show the resource in the admin menu
     *
     * @param User $auth
     * @return bool
     */

    public function showResourceMenu(User $auth)
    {
        return $auth->isSuper() || $auth->isAdmin() || $this->isAllowedFor($auth, 'fetch');
    }
}
