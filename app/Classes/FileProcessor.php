<?php

namespace App\Classes;

use App\Interfaces\FileProcessorInterface;

class FileProcessor implements FileProcessorInterface
{
    protected string $filePath;
    protected string $outputPath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->outputPath = $this->addTimestampToFileName();
    }

    public function getData(): array
    {
        $this->convertToNDJSON();

        $this->validate();

        $handle = fopen($this->outputPath, 'r');

        $result = $this->readDataLineByline($handle);
        
        if (!$result) {
            throw new \Exception("No data found in file: $this->outputPath");
        }
        
        if (!$handle) {
            throw new \Exception("Cannot open file: $this->outputPath");
        }

        fclose($handle);

        return $result;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function convertToNDJSON(): void
    {
        throw new \Exception('Method convertToNDJSON must be implemented in the child class');
    }

    protected function addTimestampToFileName(): string
    {
        $dir = pathinfo($this->filePath, PATHINFO_DIRNAME);
        $fileName = pathinfo($this->filePath, PATHINFO_FILENAME);
        $output = $dir.'/'.preg_replace('/\.[^.]+$/', '', time().'_'.$fileName) . '.ndjson';
        return $output;
    }

    public function getConvertedFilePath(): string
    {
        return $this->outputPath;
    }

    private function validate(): bool
    {
        if (!file_exists($this->filePath)) {
            throw new \Exception("File not found: $this->filePath");
        }

        if (!file_exists($this->outputPath)) {
            throw new \Exception("File could not converted to NDJSON: $this->filePath");
        }

        return true;
    }
    private function readDataLineByline($handle): array
    {
        $result = [];
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $decoded = json_decode($line, true);
            $result[] = $decoded;
        }
        return $result;
    }
}