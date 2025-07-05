<?php

namespace App\Interfaces;

interface ImportServiceInterface
{
    public function __construct(array $filters = []);
    public function shouldProcess(array $record): bool;
    public function store(array $record): void;
    public function formatData(array $record): array;
}