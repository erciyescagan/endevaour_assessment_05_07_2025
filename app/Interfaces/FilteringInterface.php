<?php

namespace App\Interfaces;

interface FilteringInterface
{
    public function applyFilter(array $record):  bool;
}