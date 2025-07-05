<?php

namespace App\Interfaces;

interface FileProcessorInterface 
{
    public function getData(): array;
    public function getFilePath(): string;
    public function convertToNDJSON(): void;
    public function getConvertedFilePath(): string;
}