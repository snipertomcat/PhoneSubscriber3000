<?php

namespace Apithy\Utilities\Model;

/**
 * Class FilterTrait
 * @package Apithy\Utilities\Model
 */
trait FilterTrait
{
    /**
     * Get an array of specified filterable fields.
     *
     * @return array
     */
    public function filterFields()
    {
        return property_exists($this, 'filterable') ? $this->filterable : [];
    }

    /**
     * Get an array of specified filterable fields for has relationships.
     *
     * @return array
     */
    public function filterCustomFields()
    {
        return property_exists($this, 'filterableCustom') ? $this->filterableCustom : [];
    }

    /**
     * Filter the query builder with the specified filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters)
    {
        $filterable = $this->filterFields();
        $filterCustom = $this->filterCustomFields();

        foreach ($filters as $fieldKey => $opts) {
            $keys = explode(',', $fieldKey);

            if (count($keys) > 1) {
                $field = $keys[1];
                $operation = in_array($keys[0], ['a', 'o', 'and', 'or']) ? $keys[0][0] : 'a';
            } else {
                $field = $keys[0];
                $operation = 'a';
            }

            if (in_array($field, $filterCustom) && is_array($opts) && isset($opts['cstm'])) {
                $query->$field($opts['cstm']);
            }

            if (!in_array($field, $filterable)) {
                continue;
            }

            $method = ($operation === 'a') ? 'where' : 'orWhere';

            /* In this case we are making a equal query */
            if (is_string($opts)) {
                $query->$method($field, '=', $opts);
                continue;
            }

            if (!is_array($opts)) {
                continue;
            }

            foreach ($opts as $key => $value) {
                switch (strtolower($key)) {
                    case 'gt':
                        $query->$method($field, '>', $value);
                        break;
                    case 'gte':
                        $query->$method($field, '>=', $value);
                        break;
                    case 'lt':
                        $query->$method($field, '<', $value);
                        break;
                    case 'lte':
                        $query->$method($field, '<=', $value);
                        break;
                    case 'ne':
                        $query->$method($field, '!=', $value);
                        break;
                    case 'eq':
                        $query->$method($field, '=', $value);
                        break;
                    case 'lk':
                        $query->$method($field, 'like', '%'.$value.'%');
                        break;
                    case 'in':
                        $values = explode(',', $value);
                        $method = sprintf('%sIn', $method);
                        $query->$method($field, $values);
                        break;
                    case 'nin':
                        $values = explode(',', $value);
                        $method = sprintf('%sNotIn', $method);
                        $query->$method($field, $values);
                        break;
                    case 'btw':
                        $values = explode(',', $value);
                        $method = sprintf('%sBetween', $method);
                        $query->$method($field, $values);
                        break;
                }
            }
        }
    }
}
