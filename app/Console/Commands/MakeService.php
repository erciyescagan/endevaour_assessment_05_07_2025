<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeService extends Command
{

    protected $signature = 'make:service {file} {--model=}';

    protected $description = 'Create a new service class in App\\Services';

    private string $fileName = "";
    private string $modelName = "";

     public function handle()
     {
        $this->fileName = $this->argument('file');
        $this->modelName = $this->option('model') ?? '';
        if($this->modelName === '') {
            $this->error("Every service class must be tied to a corresponding model.");
            return Command::FAILURE;
        }

        $path = app_path("Services/{$this->fileName}.php");
        
        $fileContent = $this->createAndGetFileContent();

        if (File::exists($path)) {
            $this->error("Service {$this->fileName} already exists!");
            return Command::FAILURE;
        }

        File::ensureDirectoryExists(app_path('Services'));
        File::put($path, $fileContent);

        $this->info("Service class {$this->fileName} created successfully.");
        return Command::SUCCESS;
    }

    public function createAndGetFileContent(): string
    {
        $content = <<<PHP
                    <?php

                    namespace App\Services;

                    use App\Classes\Service;
                    use App\Interfaces\FilteringInterface;
                    use Illuminate\Database\Eloquent\Model;
                    use App\\Models\\{$this->modelName};

                    class {$this->fileName} extends Service
                    {
                        protected array \$filters = [FilteringInterface::class];
                        protected Model \$model;
                        
                        public function __construct(array \$filters = [])
                        {
                            \$this->filters = \$filters;
                        
                            \$this->model = new {$this->modelName}(); 
                        }
                        
                        public function formatData(array \$record): array
                        {
                            return [];
                        }
                    }
                    PHP;
        return $content;
    }
}
