<?php

namespace App\Filters;

use App\Classes\Filter;

class TripleDigitFilter extends Filter
{
    public function applyFilter(array $record): bool
    {
        $number = $record['credit_card_number'] ?? null;

        if (empty($number)) {
            return false;
        }

        return preg_match('/(\d)\1\1/', $number) === 1;
    }
}
