<?php

namespace Apithy\Utilities\Model;

/**
 * Interface Searchable
 * @package Apithy\Utilities\Model
 */
interface Searchable
{
    /**
     * Scope the query by the search term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     */
    public function scopeSearch($query, $term);
}
