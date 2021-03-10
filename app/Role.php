<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Apithy\Support\Database\CacheQueryBuilder;

/**
 * Class Role
 * @package Apithy
 */
class Role extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    const ROOT_ADMIN = 10;
    const ADMIN = 9;
    const STUDENT = 7;
    const PARTNER = 8;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'super' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code' => 'string',
        'name' => 'string',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'super',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that can be sorted.
     *
     * @var array
     */
    protected $sortable = [
        'id',
        'code',
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'code',
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('name', 'like', '%' . $term . '%')
                ->orWhere('code', 'like', '%' . $term . '%');
        });
    }

    /**
     * Scope a query to only include not super roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotSuper($query)
    {
        return $query->where('super', false);
    }

    public function scopeAdminOptions($query)
    {
        return $query->whereIn('id', [self::ADMIN, self::STUDENT]);
    }

    public function scopeAllowed($query)
    {
        $auth = Auth::user();

        if (!$auth->isSuper()) {
            $query->where('super', false);
        }


        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    /**
     * Scope a query to only include not super roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuper($query)
    {
        return $query->where('super', true);
    }

    /**
     * Check is the role has users associated.
     *
     * @return boolean
     */
    public function hasUsers()
    {
        return !empty($this->users()->first());
    }

    /**
     * A role has many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * The users that belong to the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * A role has many invitations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(invitation::class);
    }

    // Functions

    public function getRoleColorClass()
    {
        switch ($this->code) {
            case 'SA':
                return 'apithy-color-super-admin';
            case 'SP':
                return 'apithy-color-support';
            case 'AD':
                return 'apithy-color-admin';
            case 'SUP':
                return 'apithy-color-supervisor';
            case 'GJ':
                return 'apithy-color-gest-jerq';
            case 'PC':
                return 'apithy-color-prov-content';
            case 'AU':
                return 'apithy-color-author';
            case 'ER':
                return 'apithy-color-enroller';
            case 'STU':
                return 'apithy-color-student';
            default:
                return 'is-danger';
        }
    }
}
