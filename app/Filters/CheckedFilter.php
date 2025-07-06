<?php

namespace App\Filters;

use App\Classes\Filter;

class CheckedFilter extends Filter
{
    public function applyFilter(array $record): bool
    {
        return isset($record['checked']) && ($record['checked'] === true || $record['checked'] === 'true');
    }
}