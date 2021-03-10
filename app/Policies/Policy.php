<?php

namespace Apithy\Policies;

use Apithy\Permission;
use Apithy\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class Policy
 * @package Apithy\Policies
 */
abstract class Policy
{
    use HandlesAuthorization;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    protected static $permissions;

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
     * @return bool|void
     */
    public function before($user, $ability)
    {
        if (!$user->active) {
            return false;
        }
        return true;
    }

    /**
     * Load crud permission from database if property resource exists.
     *
     * @return $this
     */
    protected function loadResourcePermissions()
    {
        if (!property_exists($this, 'resource')) {
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

        $permission = static::$permissions[$this->resource]->first(function ($p) use ($user, $action) {
            return $p->role_id == $user->role_id && $p->action == $action;
        });

        return $permission ? $permission->allow : false;
    }
}
