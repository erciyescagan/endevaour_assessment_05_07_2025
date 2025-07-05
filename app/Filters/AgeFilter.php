<?php

namespace App\Filters;

use Carbon\Carbon;
use App\Classes\Filter;

class AgeFilter extends Filter
{

   public function applyFilter(array $record): bool
    {
        $this->isDateOfBirthValid($record['date_of_birth']);
        $age = $this->getAge($record['date_of_birth']);
        
        return !is_null($age) && $age >= 18 && $age <= 65;
    }

    private function isDateOfBirthValid(?string $date): bool
    {
        if (!$date) {
            return false;
        }
        return true;
    }

    private function getAge(?string $date): ?int
    {
        if (!$date) {
            return null;
        }
        try {
            $normalized = $this->normalizeDate($date);
            return $normalized ? Carbon::parse($normalized)->age : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
    }
}
