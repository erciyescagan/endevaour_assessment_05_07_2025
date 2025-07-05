<?php

namespace App\FileProcessors;

use App\Classes\FileProcessor;

class json extends FileProcessor 
{
    protected string $filePath;
    protected string $outputPath;
        
    public function convertToNDJSON(): void
    {
        $data = json_decode(file_get_contents($this->filePath), true);
        if (!is_array($data)) {
            throw new \Exception('Invalid JSON array format');
        }
        $fh = fopen($this->outputPath, 'w');
        foreach ($data as $row) {
            $flattened = $this->flattenArray($row);
            fwrite($fh, json_encode($flattened, JSON_UNESCAPED_UNICODE) . "\n");
        }
        fclose($fh);
    }
    
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '_' . $key;
            if (is_array($value) && !empty($value)) {
                $result += $this->flattenArray($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}