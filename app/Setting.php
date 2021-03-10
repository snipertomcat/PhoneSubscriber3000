<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package Apithy
 */
class Setting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'company_id' => 'integer',
        'setting' => 'string',
        'value' => 'string',
        'module' => 'string',
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
        'user_id',
        'company_id',
        'setting',
        'value',
        'module'
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
     * Scope a query to get a setting in a module.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeModuleSetting($query, $setting)
    {
        $query
            ->where('module', '=', $setting['module'])
            ->where('setting', '=', $setting['setting']);

        return $query;
    }
}
