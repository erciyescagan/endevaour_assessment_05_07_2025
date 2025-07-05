<?php

namespace App\Classes;

use App\Interfaces\FileProcessorInterface;
use App\Interfaces\FilteringInterface;
use App\Interfaces\ImportServiceInterface;
use Illuminate\Database\Eloquent\Model;

abstract class Service implements ImportServiceInterface
{
    protected array $filters = [FilteringInterface::class];
    protected Model $model;
    protected FileProcessorInterface $fileProcessor;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function shouldProcess(array $record): bool
    {
        foreach ($this->filters as $filter) {
            if (!$filter->applyFilter($record)) {
                return false;
            }
        }
        return true;
    }

    public function store(array $record): void
    {
        $data = $this->formatData($record);
        $this->model::create($data);
    }

    public function formatData(array $record): array
    {
        throw new \Exception('Method formatData must be implemented in the child class');
    }
}