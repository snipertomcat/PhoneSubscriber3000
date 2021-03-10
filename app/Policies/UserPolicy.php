<?php

namespace Apithy\Policies;

use Apithy\User;
use Apithy\Utilities\Model\Policy;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPolicy
 * @package Apithy\Policies
 */
class UserPolicy extends Policy
{
    /**
     * @inheritdoc
     *
     * @var string
     */
    protected $resource = User::class;

    /**
     * Determine if the authenticated user enter to index
     *
     * @param User $auth
     * @return bool
     */
    public function index(User $auth)
    {
        return $auth->isSuper() || $auth->isAdmin();
    }

    /**
     * Determine whether the authenticated user can view the user.
     *
     * @param  \Apithy\User  $auth
     * @param  Model $user
     * @return mixed
     */
    public function view(User $auth, Model $user)
    {
        if ($auth->isAdmin() || $auth->id === $user->id) {
            return true;
        }

        return parent::view($auth, $user);
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \Apithy\User  $user
     * @return mixed
     */

    public function create(User $auth)
    {
        return $auth->isSuper() || $auth->isAdmin() || $auth->isAllowedFor($auth, 'create');
    }

    /**
     * Determine whether the authenticated user can edit the user.
     *
     * @param  \Apithy\User  $auth
     * @param  Model  $user
     * @return mixed
     */
    public function edit(User $auth, Model $user)
    {
        return $auth->isSuper() || $auth->isAdmin() || $auth->isAllowedFor($auth, 'edit');
    }

    /**
     * Determine whether the authenticated user can update the user.
     *
     * @param  \Apithy\User  $auth
     * @param  Model  $user
     * @return mixed
     */
    public function update(User $auth, Model $user)
    {
        /* @var $user User */
        if ($auth->id === $user->id || $user->isAdmin()) {
            return true;
        }

        if ($user->isSuper()) {
            return false;
        }

        return parent::update($auth, $user);
    }

    /**
     * Determine whether the authenticated user can delete the user.
     *
     * @param  \Apithy\User  $auth
     * @param  Model  $user
     * @return mixed
     */
    public function delete(User $auth, Model $user)
    {
        /* @var $user User */
        if ($auth->id === $user->id) {
            return false;
        }

        if ($user->isSuper()) {
            return false;
        }

        if ($auth->isAdmin()) {
            if ($user->company[0]->id == $auth->company[0]->id) {
                return true;
            }
        }



        return parent::delete($auth, $user);
    }

    /**
     * Determine whether the authenticated user can update the user's role.
     *
     * @param  \Apithy\User  $auth
     * @param  \Apithy\User  $user
     * @return mixed
     */
    public function updateRole(User $auth, User $user)
    {
        return !$user->isSuper() && $auth->id !== $user->id;
    }

    /**
     * Determine whether the authenticated user can update the user's company.
     *
     * @param  \Apithy\User  $auth
     * @param  \Apithy\User  $user
     * @return mixed
     */
    public function updateCompany(User $auth, User $user)
    {
        return $auth->isSuper() || $auth->isAdmin();
    }

    /**
     * Determine whether the authenticated user can update the user's position.
     *
     * @param User $auth
     * @param User $user
     * @return bool
     */
    public function updatePosition(User $auth, User $user)
    {
        return $auth->isSuper() || $auth->isAdmin();
    }

    /**
     * Determine whether the authenticated user can update the user's activation status.
     *
     * @param  \Apithy\User  $auth
     * @param  \Apithy\User  $user
     * @return mixed
     */
    public function updateActive(User $auth, User $user)
    {
        $validAuth = $auth->isSuper() || $auth->isAdmin() ||  $this->isAllowedFor($auth, 'updateActive');
        return $validAuth && !$user->isSuper() && $auth->id !== $user->id;
    }

    /**
     * Determine whether the authenticated user can update the user's banned status.
     *
     * @param  \Apithy\User  $auth
     * @param  \Apithy\User  $user
     * @return mixed
     */
    public function updateBanned(User $auth, User $user)
    {
        return $this->updateActive($auth, $user);
    }

    /**
     * Only invited users can change own password no one else.
     *
     * @param User $auth
     * @param User $user
     * @return bool
     */
    public function changePassword(User $auth, User $user)
    {
        return $auth->id == $user->id;
    }

    /**
     * Profile avatar can only been saved for a super user or same user.
     *
     * @param User $auth
     * @param User $user
     * @return bool
     */
    public function changeAvatar(User $auth, User $user)
    {
        return ($auth->id === $user->id);
    }

    /**
     * Determine if the authenticated user is super role to be able to view the view
     *
     * @param User $auth
     * @return bool
     */
    public function showWindowSuper(User $auth)
    {
        return $auth->isSuper();
    }

    /**
     * Determine if the authenticated user can get the items in the resource
     *
     * @param User $auth
     * @return bool
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
