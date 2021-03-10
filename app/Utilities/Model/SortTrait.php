<?php

namespace Apithy\Utilities\Model;

/**
 * Class SortTrait
 * @package Apithy\Utilities\Model
 */
trait SortTrait
{
    /**
     * Get an array of specified sortable fields.
     *
     * @return array
     */
    public function sortFields()
    {
        return property_exists($this, 'sortable') ? $this->sortable : [];
    }

    /**
     * Sort with direction the query builder with the specified options.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @param string $direction
     */
    public function scopeSort($query, $sort, $direction = 'asc')
    {
        $sortable = $this->sortFields();

        if (!in_array($sort, $sortable)) {
            return;
        }

        $direction = strtolower($direction);

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);
    }
}
