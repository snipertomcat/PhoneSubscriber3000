<?php

namespace Apithy\Utilities\Model;

/**
 * Interface Sortable
 * @package Apithy\Utilities\Model
 */
interface Sortable
{
    /**
     * Get an array of specified sortable fields.
     *
     * @return array
     */
    public function sortFields();

    /**
     * Sort with direction the query builder with the specified options.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @param string $direction
     */
    public function scopeSort($query, $sort, $direction = 'asc');
}
