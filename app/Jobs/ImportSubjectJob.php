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
    protected int $startIndex;
    protected int $endIndex;
    protected string $convertedFilePath;
    protected ImportServiceInterface $service;
    protected FileProcessorInterface $fileProcessor;

   
    public function __construct(string $file, $startIndex = null, $endIndex = null)
    {
        $this->file = $file;
        $this->extension = pathinfo($file, PATHINFO_EXTENSION);
        $this->startIndex = $startIndex ?? 0;
        $this->endIndex = $endIndex ?? 0;
        $this->initializeService();
        $this->initializeFileProcessor();
    }

    public function handle(): void
    {
        $importState = $this->getOrCreateImportState();

        $data = $this->fileProcessor->getData($this->file);
    
        $start = $this->resolveStartIndex($importState);
    
        $end = $this->resolveEndIndex($data);

        for ($i = $start; $i < $end; $i++) {

            $record = $data[$i] ?? null;
            $record = $this->addFileInfoToRecord($record);
            if (!is_array($record)) {
                continue;
            }
            if ($this->service->shouldProcess($record)) {
                $this->service->store($record);
            }
            $this->updateImportState($importState, $i + 1);
        }
    }

    private function initializeService(): void
    {
        $this->service = new ImportSubjectService([
            new AgeFilter(),
            new CheckedFilter(),
            new TripleDigitFilter()
        ]);
    }

    private function initializeFileProcessor(): void
    {
        $className = "\\App\\FileProcessors\\" . $this->extension;

        if (!class_exists($className)) {
            throw new Exception("File processor for extension {$this->extension} not found.");
        } else {
            
        }
        $this->fileProcessor = new $className($this->file);
        $this->convertedFilePath = $this->fileProcessor->getConvertedFilePath();

    }

  
   
    private function getOrCreateImportState(): ImportState
    {
        return ImportState::firstOrCreate(
            ['file_path' => $this->file],
            ['converted_file_path' => $this->convertedFilePath],
            ['last_processed_index' => 0]
        );
    }
   
    private function resolveStartIndex(ImportState $importState): int
    {
        if ($this->isFilled($this->startIndex)) {
            return (int)$this->startIndex;
        }
        return (int)($importState->last_processed_index ?? 0);
    }

 
    private function resolveEndIndex(array $data): int
    {
        if ($this->isFilled($this->endIndex) && (int)$this->endIndex <= count($data)) {
            return (int)$this->endIndex;
        }
        return count($data);
    }

  
    private function isFilled($value): bool
    {
        return isset($value) && $value !== '' && $value !== false && $value !== null && $value !== 0;
    }

 
    private function updateImportState(ImportState $importState, int $lastProcessedIndex): void
    {
        $importState->last_processed_index = $lastProcessedIndex;
        $importState->save();
    }

    private function addFileInfoToRecord(array $record): array
    {
        $record['from_which_file'] = $this->fileProcessor->getFilePath();
        return $record;
    }   


}
