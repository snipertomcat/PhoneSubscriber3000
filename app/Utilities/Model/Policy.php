<?php

namespace Apithy\Utilities\Model;

use Apithy\Mail\UserRegister;
use Apithy\Permission;
use Apithy\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Policy
 * @package Apithy\Policies
 */
class Policy
{
    use HandlesAuthorization;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    protected static $permissions;

    /**
     * Resource class name for auto loading permissions.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (is_null(static::$permissions)) {
            static::$permissions = collect();
        }
        $this->loadResourcePermissions();
    }

    /**
     * @param \Apithy\User $user
     * @param string $ability
     * @return mixed
     */
    public function before($user, $ability)
    {
        if ($user->isSuper() && $user->status == User::STATUS_ACTIVE) {
            return true;
        }

        if ($user->status != User::STATUS_ACTIVE) {
            return false;
        }
    }

    /**
     * Load crud permission from database if property resource exists.
     *
     * @return $this
     */
    protected function loadResourcePermissions()
    {
        if (!property_exists($this, 'resource') || empty($this->resource)) {
            return $this;
        }

        if (!isset(static::$permissions[$this->resource])) {
            static::$permissions[$this->resource] = Permission::forResource($this->resource)->get();
        }

        return $this;
    }

    /**
     * Verify if the previous loaded resource permissions is allowed
     * for the given user role.
     *
     * @param \Apithy\User $user
     * @param string $action
     * @return bool
     */
    protected function isAllowedFor(User $user, $action)
    {
        if (!isset(static::$permissions[$this->resource])) {
            return false;
        }

        $roles = $user->roles;

        $permission = static::$permissions[$this->resource]->first(function ($p) use ($roles, $action) {
            return $roles->contains('id', $p->role_id) && $p->action == $action;
        });

        return $permission ? $permission->allow : false;
    }

    /**
     * Determine whether the user can view the level type.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return mixed
     */
    public function view(User $user, Model $model)
    {
        return $user->isSuper() || $this->isAllowedFor($user, 'view');
    }

    /**
     * Determine whether the user can create level types.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuper() || $this->isAllowedFor($user, 'create');
    }

    /**
     * Determine whether the user can update the level type.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return $user->isSuper() || $this->isAllowedFor($user, 'update');
    }

    /**
     * Determine whether the user can delete the level type.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return mixed
     */
    public function delete(User $user, Model $model)
    {
        return $user->isSuper() || $this->isAllowedFor($user, 'delete');
    }

    /**
     * Determine whether the user get the items in the resource.
     *
     * @param  User  $user
     * @param  Model  $model
     * @return mixed
     */

    public function fetch(User $auth)
    {
        return $auth->isSuper() || $this->isAllowedFor($auth, 'fetch');
    }

    /**
     * Determine whether the user can show the menu item.
     *
     * @param  \Apithy\User  $auth
     * @return mixed
     */

    public function showResourceMenu(User $auth)
    {
        return $auth->isSuper() || $this->isAllowedFor($auth, 'fetch');
    }
}
