<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFilter extends Command
{
   
    protected $signature = 'make:filter {fileName}';

    protected $description = 'Create a new filter class in App\\Filters';

     public function handle()
    {
        $fileName = Str::studly($this->argument('fileName'));
        
        $path = app_path("Filters/{$fileName}.php");

        $fileContent = $this->createAndGetFileContent($fileName);

        if (File::exists($path)) {
            $this->error("Filter {$fileName} already exists!");
            return Command::FAILURE;
        }

        File::ensureDirectoryExists(app_path('Filters'));
        File::put($path, $fileContent);

        $this->info("Filter class {$fileName} created successfully.");
        return Command::SUCCESS;
    }

    public function createAndGetFileContent(string $fileName): string
    {
        $content = <<<PHP
                    <?php

                    namespace App\Filters;

                    use App\Classes\Filter;

                    class {$fileName} extends Filter
                    {
                        public function applyFilter(array \$record): bool
                        {
                            return true;
                        }
                    }
                    PHP;
        return $content;
    }
}
