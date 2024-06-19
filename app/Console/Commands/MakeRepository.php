<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $suppliedName = $this->argument('name');

        $this->createRepository($suppliedName);

        return 0;
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
    function initRepositoryAbstractClass()
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
