<?php

namespace App\Services;

use App\Models\Subject;
use Carbon\Carbon;
use App\Classes\Service;
use App\Interfaces\FilteringInterface;
use App\Filters\AgeFilter;
use Illuminate\Database\Eloquent\Model;
use Psy\Sudo;

class ImportSubjectService extends Service
{
    /**
     * @var FilteringInterface[]
     */
    protected array $filters = [FilteringInterface::class];

    protected Model $model;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->model = new Subject();
    }

    public function formatData(array $record): array
    {
        return [
            'name' => $record['name'] ?? null,
            'email' => $record['email'] ?? null,
            'address' => $record['address'] ?? null,
            'date_of_birth' => $this->normalizeDate($record['date_of_birth'] ?? null),
            'description' => $record['description'] ?? null,
            'checked' => $record['checked'] === "true" || $record['checked'] ? true : false,
            'interest' => $record['interest'] ?? null,
            'account' => $record['account'] ?? null,
            'credit_card_type' => $record['credit_card_type'] ?? null,
            'credit_card_number' => $record['credit_card_number'] ?? null,
            'credit_card_name' => $record['credit_card_name'] ?? null,
            'credit_card_expiration' => $record['credit_card_expirationDate'] ?? null,
            'from_which_file' => $record['from_which_file'] ?? null,
        ];
    }

    private function normalizeDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $normalized = $this->tryParseDate($date, 'Y-m-d');
        if ($normalized) {
            return $normalized;
        }

        $normalized = $this->tryParseDate($date, 'd/m/Y');
        return $normalized;
    }

    private function tryParseDate(string $date, string $format): ?string
    {
        try {
            if ($format === 'Y-m-d') {
                return Carbon::parse($date)->format('Y-m-d');
            }
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
   
}
