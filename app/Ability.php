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
 * Class Invitation
 * @package Apithy
 */
class Ability extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'abilities';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'company_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'company_id',
    ];

    protected $sortable = [
        'id',
        'title',
        'company_id',
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
        'title',
        'company_id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'company_name',
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('title', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    public function scopeAllowed($query)
    {
        $auth = Auth::user();
        if ($auth->can('fetch', self::class)) {
            if (!$auth->isSuper()) {
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
     * The role that belongs to the invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_ability');
    }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'abilities_session');
    }

    /**
     * The Company that belongs to the invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getCompanyNameAttribute()
    {
        $company = $this->company()->first();
        if ($company) {
            return $company->name;
        } else {
            return "Apithy";
        }
    }
}
