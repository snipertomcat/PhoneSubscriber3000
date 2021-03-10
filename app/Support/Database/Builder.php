<?php

namespace Apithy\Support\Database;

use Cache;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder extends QueryBuilder
{
    /**
     * Run the query as a "select" statement against the connection.
     *
     * @return array
     */
    protected function runSelect()
    {
        $nocache_urls = [
            'profile\/edit',
            'budget\/create' .
            'student\/progress',
            'view',
            'edit',
            'update',
            'create',
            'register\/confirm',
            'register',
            'enroll'
        ];

        $nocache_patters = '/' . implode('|', $nocache_urls) . '/';


        if (request()->hasHeader('x-apithy-nocache') ||
            request()->ajax()
        ) {
            return parent::runSelect();
        }

        if (preg_match($nocache_patters, request()->path())) {
            return parent::runSelect();
        }

        return Cache::store('file')->remember($this->getCacheKey(), 0.20, function () {
            return parent::runSelect();
        });


    }

    /**
     * Returns a Unique String that can identify this Query.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return json_encode([
            $this->toSql() => $this->getBindings()
        ]);
    }
}
