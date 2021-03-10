<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\HashIdTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanyArea
 * @package Apithy
 */
class CompanyArea extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, HashIdTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_areas';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'parent_id' => 'integer',
        'name' => 'string',
        'short_name' => 'string',
        'description' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'short_name',
        'description',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that can be sorted.
     *
     * @var array
     */
    protected $sorteable = [
        'id',
        'company_id',
        'name',
        'short_name',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'company_id',
        'name',
        'short_name',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'users_details',
    ];

    /**
     * Scope the query by the search term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('name', 'like', "%$term%")
                ->orWhere('short_name', 'like', "%$term%");
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAllowed($query)
    {
        $auth = Auth::user();
        if (!$auth->isSuper()) {
            if ($auth->can('fetch', [CompanyArea::class, $auth->company[0]])) {
                $companyId = $auth->company[0]->id;
                $query->whereHas('company', function ($q) use ($companyId) {
                    $q->where('id', $companyId);
                });
            }
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    /**
     * Get the company of an area.
     *
     * @param $company_id
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function positions()
    {
        return $this->hasMany(CompanyPosition::class, 'area_id', 'id');
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = (($value >= 0) ? $value : null);
    }

    public function users()
    {
        $users = $this->hasManyThrough(
            User::class,
            CompanyPosition::class,
            'area_id',
            'id',
            'id',
            'id'
        );
        return $users;
    }

    public function getUsersDetailsAttribute()
    {
        $total = 0;
        $ids = new Collection();
        $users = new Collection();

        $this->positions()->each(function ($position) use (&$total, $ids, $users) {
            $position->users->each(function ($user) use (&$total, $ids, $users) {
                if ($user->activated && $user->status === User::STATUS_ACTIVE) {
                    $users->push([
                        'id' => $user->id,
                        'name' => $user->name,
                        'licences' => $user->licences
                    ]);
                    $ids->push($user->id);
                    $total += 1;
                }
            });
        });

        return ['total' => $total, 'ids' => $ids, 'users' => $users];
    }
}
