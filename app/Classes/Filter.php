<?php

namespace App\Classes;

use App\Interfaces\FilteringInterface;

abstract class Filter implements FilteringInterface
{
    abstract public function applyFilter(array $record): bool;
}