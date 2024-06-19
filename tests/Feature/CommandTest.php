<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up created files before each test.
    File::delete(app_path('Concerns/ApiResponses.php'));
    File::delete(app_path('Services/BaseService.php'));
    File::delete(app_path('Services/TestService.php'));
    File::delete(app_path('Abstracts/AbstractRepository.php'));
    File::delete(app_path('Repositories/TestRepository.php'));
});

afterEach(function () {
    // Clean up created files after each test.
    File::delete(app_path('Concerns/ApiResponses.php'));
    File::delete(app_path('Services/BaseService.php'));
    File::delete(app_path('Services/TestService.php'));
    File::delete(app_path('Abstracts/AbstractRepository.php'));
    File::delete(app_path('Repositories/TestRepository.php'));
});

it('can create a service', function () {
    // Run the Artisan command to create a service.
    $this->artisan('make:service', ['name' => 'Test'])
        ->expectsConfirmation('Would you like to create a corresponding repository?', 'no')
        ->assertExitCode(0);

    // Check if the service file is created.
    $this->assertTrue(File::exists(app_path('Services/TestService.php')));

    // Verify the content of the created service file.
    $content = File::get(app_path('Services/TestService.php'));
    expect($content)->toContain('class TestService');
});

it('can create a repository', function () {
    // Run the Artisan command to create a repository.
    Artisan::call('make:repository', ['name' => 'Test']);

    // Check if the repository file is created.
    $this->assertTrue(File::exists(app_path('Repositories/TestRepository.php')));

    // Verify the content of the created repository file.
    $content = File::get(app_path('Repositories/TestRepository.php'));
    expect($content)->toContain('class TestRepository');
});

it('prompts to create a repository after creating a service', function () {
    // Mock the user input to confirm repository creation.
    $this->artisan('make:service', ['name' => 'Test'])
         ->expectsConfirmation('Would you like to create a corresponding repository?', 'yes')
         ->assertExitCode(0);

    // Check if both service and repository files are created.
    $this->assertTrue(File::exists(app_path('Services/TestService.php')));
    $this->assertTrue(File::exists(app_path('Repositories/TestRepository.php')));

    // Verify the content of the created repository file.
    $content = File::get(app_path('Repositories/TestRepository.php'));
    expect($content)->toContain('class TestRepository');
});

