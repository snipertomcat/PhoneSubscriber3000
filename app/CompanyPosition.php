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
 * Class CompanyPosition
 * @package Apithy
 */
class CompanyPosition extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, HashIdTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_positions';

    public $campos = [
        'name' => 'name',
        'short_name' => 'short_name',
        'description' => 'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'area_id' => 'integer',
        'name' => 'string',
        'short_name' => 'string',
        'description' => 'string'
    ];

    /**
     * The attributes that should de mutated to dates.
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
     */
    protected $sorteable = [
        'id',
        'area_id',
        'name',
        'short_name',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that can be filtered.
     */
    protected $filterable = [
        'id',
        'area_id',
        'name',
        'short_name',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'users_details'
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

    public function scopeAllowed($query)
    {
        $auth = Auth::user();
        if (!$auth->isSuper()) {
            if ($auth->can('fetch', [CompanyPosition::class, $auth->company[0]])) {
                $companyId = $auth->company[0]->id;
                $query->whereHas('company', function ($q) use ($companyId) {
                    $q->where('companies.id', $companyId);
                });
            }
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    /**
     * Get all the areas of this position.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(CompanyArea::class);
    }

    public function company()
    {
        return $this->hasManyThrough(Company::class, CompanyArea::class, 'id', 'id', 'area_id', 'company_id');
    }

    /**
     * Return all users of this position
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_position_user', 'company_position_id', 'user_id');
    }

    public function getUsersDetailsAttribute()
    {
        $total = 0;
        $ids = new Collection();
        $users = new Collection();

        $this->users->each(function ($user) use (&$total, $ids, $users) {
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

        return ['total' => $total, 'ids' => $ids, 'users' => $users];
    }
}
