<?php

namespace App\FileProcessors;

use App\Classes\FileProcessor;

class csv extends FileProcessor {
    
    protected string $filePath;
    protected string $outputPath;

    public function convertToNDJSON(): void
    {
        $fh = fopen($this->filePath, 'r');
        $headers = fgetcsv($fh);
        $out = fopen($this->outputPath, 'w');
        while (($row = fgetcsv($fh)) !== false) {
            $assoc = array_combine($headers, $row);
            fwrite($out, json_encode($assoc, JSON_UNESCAPED_UNICODE) . "\n");
        }
        fclose($fh);
        fclose($out);

    }
    
    
}