<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFileProcessor extends Command
{

    protected $signature = 'make:file-processor {fileName}';

    protected $description = 'Create a new file processor class in App\\FileProcessors';

     public function handle()
    {
        $fileName = $this->argument('fileName');
        
        $path = app_path("FileProcessors/{$fileName}.php");

        $fileContent = $this->createAndGetFileContent($fileName);

        if (File::exists($path)) {
            $this->error("File Processor {$fileName} already exists!");
            return Command::FAILURE;
        }

        File::ensureDirectoryExists(app_path('FileProcessors'));
        File::put($path, $fileContent);

        $this->info("File Processor class {$fileName} created successfully.");
        return Command::SUCCESS;
    }

    public function createAndGetFileContent(string $fileName): string
    {
        $content = <<<PHP
                    <?php

                    namespace App\FileProcessors;

                    use App\Classes\FileProcessor;

                    class {$fileName} extends FileProcessor
                    {
                        public function convertToNDJSON(): void
                        {
                            //
                        }
                    }
                    PHP;
        return $content;
    }
}
