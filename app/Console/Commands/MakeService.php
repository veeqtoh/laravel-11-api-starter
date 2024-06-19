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
        $this->initApiResponseTrait();
        $this->initBaseServiceClass();

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
            $this->createRepository($suppliedName);
        }
        return 0;
    }

    /**
     * Initialize the ApiResponse trait.
     */
    public function initApiResponseTrait()
    {
        $traitName = 'ApiResponses';
        $traitPath = app_path("Concerns/{$traitName}.php");

        if (!File::exists($traitPath)) {
            $traitStub = File::get(resource_path('stubs/api-responses.stub'));

            File::ensureDirectoryExists(app_path('Concerns'));
            File::put($traitPath, $traitStub);

            $this->info('ApiResponse trait created just now.');
        }
    }

    /**
     * Initialize the base service class.
     */
    public function initBaseServiceClass()
    {
        $baseServiceClassName = 'BaseService';
        $baseServiceClassPath = app_path("Services/{$baseServiceClassName}.php");

        if (!File::exists($baseServiceClassPath)) {
            $baseServiceClassStub = File::get(resource_path('stubs/base-service.stub'));

            File::ensureDirectoryExists(app_path('Services'));
            File::put($baseServiceClassPath, $baseServiceClassStub);

            $this->info('Base service class created just now.');
        }

    }

    /**
     * Create a repository class alongside the service class.
     *
     * @param string $suppliedName The name of the repository class to create.
     */
    public function createRepository($suppliedName)
    {
        $repositoryName = $suppliedName . 'Repository';
        $repositoryPath = app_path("Repositories/{$repositoryName}.php");

        if (File::exists($repositoryPath)) {
            $this->error('Repository already exists!');
            return 1;
        }

        $this->initRepositoryAbstractClass();

        $this->info('Just extending the abstract repository class..');

        $repositoryStub = File::get(resource_path('stubs/repository.stub'));
        $repositoryStub = str_replace('{{ class }}', $repositoryName, $repositoryStub);

        File::ensureDirectoryExists(app_path('Repositories'));
        File::put($repositoryPath, $repositoryStub);

        $this->info("Repository created successfully: {$repositoryPath}");
    }

    /**
     * Initialize the repository abstract class.
     */
    public function initRepositoryAbstractClass()
    {
        $abstractClassName = 'AbstractRepository';
        $abstractPath      = app_path("Abstracts/{$abstractClassName}.php");

        if (!File::exists($abstractPath)) {
            $abstractStub = File::get(resource_path('stubs/abstract-repository.stub'));

            File::ensureDirectoryExists(app_path('Abstracts'));
            File::put($abstractPath, $abstractStub);

            $this->info('Abstract class created just now.');
        }

    }
}
