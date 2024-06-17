<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $suppliedName = $this->argument('name');
        $name         = $suppliedName . 'Service';
        $path         = app_path("Services/{$name}.php");

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return 1;
        }

        $stub = File::get(resource_path('stubs/service.stub'));
        $stub = str_replace('{{ class }}', $name, $stub);

        File::ensureDirectoryExists(app_path('Services'));
        File::put($path, $stub);

        $this->info("Service created successfully: {$path}");

        if ($this->confirm('Would you like to create a corresponding repository?', true)) {
            $repositoryName = $suppliedName . 'Repository';
            $repositoryPath = app_path("Repositories/{$repositoryName}.php");

            if (File::exists($repositoryPath)) {
                $this->error('Repository already exists!');
                return 1;
            }

            $repositoryStub = File::get(resource_path('stubs/repository.stub'));
            $repositoryStub = str_replace('{{ class }}', $repositoryName, $repositoryStub);

            File::ensureDirectoryExists(app_path('Repositories'));
            File::put($repositoryPath, $repositoryStub);

            $this->info("Repository created successfully: {$repositoryPath}");
        }
        return 0;
    }
}
