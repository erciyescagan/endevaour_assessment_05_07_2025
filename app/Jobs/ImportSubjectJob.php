<?php

namespace App\Jobs;

use App\FileProcessors\csv;
use App\FileProcessors\json;
use App\Filters\AgeFilter;
use App\Filters\CheckedFilter;
use App\Filters\TripleDigitFilter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ImportState;
use App\Services\ImportSubjectService;
use Exception;
use App\Interfaces\ImportServiceInterface;
use App\Interfaces\FileProcessorInterface;

class ImportSubjectJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected string $file;
    protected string $extension = 'json';
    protected string $convertedFilePath;
    protected ImportServiceInterface $service;
    protected FileProcessorInterface $fileProcessor;
    protected ImportState $importState;

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->extension = pathinfo($file, PATHINFO_EXTENSION);

        $this->initializeService();
        $this->initializeFileProcessor();
        $this->importState = $this->getOrCreateImportState();
    }

    public function handle(): void
    {

        $data = $this->fileProcessor->getData($this->file);

        $start = (int) ($this->importState->last_processed_index ?? 0);
        $end = count($data);

        for ($i = $start; $i < $end; $i++) {
            $record = $data[$i] ?? null;
            $record = $this->addFileInfoToRecord($record);

            if (!is_array($record)) {
                continue;
            }

            if ($this->service->shouldProcess($record)) {
                $this->service->store($record);
            }

            $this->updateImportState($i + 1);
        }

    }

    private function initializeService(): void
    {
        $this->service = new ImportSubjectService([
            new AgeFilter()
        ]);
    }

    private function initializeFileProcessor(): void
    {
        $className = "\\App\\FileProcessors\\" . $this->extension;

        if (!class_exists($className)) {
            throw new Exception("File processor for extension {$this->extension} not found.");
        }

        $this->fileProcessor = new $className($this->file);
        $this->convertedFilePath = $this->fileProcessor->getConvertedFilePath();
    }

    private function getOrCreateImportState(): ImportState
    {
        return ImportState::firstOrCreate(
            ['file_path' => $this->file],
            [
                'converted_file_path' => $this->fileProcessor->getConvertedFilePath(),
                'last_processed_index' => 0
            ]
        );
    }

    private function updateImportState(int $lastProcessedIndex): void
    {
        $this->importState->last_processed_index = $lastProcessedIndex;
        $this->importState->save();
    }

    private function addFileInfoToRecord(array $record): array
    {
        $record['from_which_file'] = $this->fileProcessor->getFilePath();
        return $record;
    }
}
