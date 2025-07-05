<?php

namespace App\Console\Commands;

use App\Jobs\ImportSubjectJob;
use Illuminate\Console\Command;

class SubjectFileImporterCommand extends Command
{
    protected $signature = 'import:file {file} {--start=} {--end=}';

    protected $description = 'Import datas from a file';
    
    protected string $file;
    protected int $startIndex;
    protected int $endIndex;

    public function __construct()
    {
        parent::__construct();
        $this->file = '';
        $this->startIndex = 0;
        $this->endIndex = 0;
    }

    public function handle(): int
    {
        $this->info('Starting file import process...');
        $result = $this->setup();
        
        if ($result === Command::SUCCESS) {
            $this->info('File import process completed successfully.');
        } else {
            $this->error('File import process failed.');
        }
        
        return $result;
    }

    public function setup(): int
    {
        $this->setArgumentsAndOptions();

        if (!$this->validate()) {
            return Command::FAILURE;
        }

        return $this->dispatchJob();
    }

    public function setArgumentsAndOptions(): void
    {
        $this->file = $this->argument('file');
        $this->startIndex = $this->parseIndex($this->option('start'));
        $this->endIndex = $this->parseIndex($this->option('end'));
    }

    public function dispatchJob(): int
    {
        ImportSubjectJob::dispatch($this->file, $this->startIndex, $this->endIndex);
        $this->info('Import job dispatched successfully.');
        return Command::SUCCESS;
    }

    public function validate(): bool
    {
        if (!$this->isValidFile($this->file)) {
            return $this->failWithMessage("File not found: {$this->file}");
        }
        if (!$this->isValidIndex($this->startIndex)) {
            return $this->failWithMessage('Start index must be a non-negative integer.');
        }
        if (!$this->isValidIndex($this->endIndex)) {
            return $this->failWithMessage('End index must be a non-negative integer.');
        }
        return true;
    }

    private function parseIndex($index): int
    {
        return is_numeric($index) && $index >= 0 ? (int)$index : 0;
    }

    private function isValidFile($file): bool
    {
        return file_exists($file);
    }

    private function isValidIndex($index): bool
    {
        return is_int($index) && $index >= 0;
    }

    private function failWithMessage(string $message): int
    {
        $this->error($message);
        return Command::FAILURE;
    }
}
