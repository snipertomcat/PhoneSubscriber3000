<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Apithy\Support\Database\CacheQueryBuilder;

class Tag extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait, CacheQueryBuilder;

    protected $table = 'tags';

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'company_id' => 'integer',
    ];

    protected $fillable = [
        'title',
        'company_id',
    ];

    protected $sortable = [
        'title',
        'company_id',
    ];

    protected $filterable = [
        'title',
        'company_id',
        'company_name'
    ];

    protected $appends = [
        'company_name'
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
                ->orWhere('title', 'like', '%' . $term . '%');
        });
    }

    public function scopeAllowed($query)
    {
        //
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'id');
    }

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
