<?php

namespace App\Contract;

trait EloquentScopeRuleTrait
{
    protected function moreThan($query, $column, $value)
    {
        $query->where($column, '>', $value);
    }

    protected function moreThanEqual($query, $column, $value)
    {
        $query->where($column, '>=', $value);
    }

    protected function lessThan($query, $column, $value)
    {
        $query->where($column, '<', $value);
    }

    protected function lessThanEqual($query, $column, $value)
    {
        $query->where($column, '<', $value);
    }

    protected function equal($query, $column, $value)
    {
        $query->where($column, $value);
    }
}
