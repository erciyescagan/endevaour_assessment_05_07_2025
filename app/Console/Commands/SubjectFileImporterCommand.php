<?php

namespace App\Console\Commands;

use App\Jobs\ImportSubjectJob;
use Illuminate\Console\Command;

class SubjectFileImporterCommand extends Command
{
    protected $signature = 'import:file {filename : File name, e.g. subjects.ndjson}';
    protected $description = 'Dispatches a background job to import subjects from a file';

    public function handle(): int
    {
        $filename = $this->argument('filename');
        $filePath = storage_path("app/{$filename}");

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        ImportSubjectJob::dispatch($filename);

        $this->info("Import job dispatched for file: {$filename}");
        return self::SUCCESS;
    }
}
