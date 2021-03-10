<?php

namespace Apithy\Utilities\Model;

/**
 * Interface Filterable
 * @package Apithy\Utilities\Model
 */
interface Filterable
{
    /**
     * Get an array of specified filterable fields.
     *
     * @return array
     */
    public function filterFields();

    /**
     * Get an array of specified filterable fields for customs.
     *
     * @return array
     */
    public function filterCustomFields();

    /**
     * Filter the query builder with the specified filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters);
}
