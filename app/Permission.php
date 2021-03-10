<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package Apithy
 */
class Permission extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'resource' => 'string',
        'action' => 'string',
        'allow' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'resource',
        'action',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    /**
     * Set allow attribute, only if changed.
     *
     * @param boolean $allow
     * @return $this
     */
    public function setAllow($allow)
    {
        if ($this->allow != $allow) {
            $this->allow = !! $allow;
        }
        return $this;
    }

    /**
     * A permission has one role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Scope a query to load all permissions for the given resource key.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $resource
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForResource($query, $resource)
    {
        return $query->where('resource', $resource);
    }
}
