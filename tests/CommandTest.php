<?php

namespace LaravelArchitex\Tests;

use LaravelArchitex\Console\Commands\MakeRepositoryCommand;
use LaravelArchitex\Console\Commands\MakeServiceCommand;
use LaravelArchitex\Console\Commands\MakeDDDCommand;
use LaravelArchitex\Services\ArchitectureGenerator;
use Illuminate\Filesystem\Filesystem;

class CommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        $this->app['config']->set('architex', [
            'patterns' => [
                'repository' => [
                    'enabled' => true,
                    'path' => 'app/Repositories',
                    'namespace' => 'App\\Repositories',
                ],
                'service' => [
                    'enabled' => true,
                    'path' => 'app/Services',
                    'namespace' => 'App\\Services',
                ],
                'ddd' => [
                    'enabled' => true,
                    'layers' => [
                        'domain' => [
                            'path' => 'app/Domain',
                            'namespaces' => [
                                'entities' => 'App\\Domain\\Entities',
                            ],
                        ],
                    ],
                ],
            ],
            'naming' => [
                'case' => 'pascal',
                'suffixes' => [
                    'repository' => 'Repository',
                    'repository_interface' => 'RepositoryInterface',
                    'service' => 'Service',
                ],
            ],
            'templates' => [
                'stub_path' => base_path('stubs/architex'),
                'default_stub_path' => __DIR__ . '/../stubs',
                'variables' => [
                    'app_namespace' => 'App',
                    'author' => 'Laravel Architex',
                    'year' => date('Y'),
                ],
            ],
        ]);
    }

    public function test_make_repository_command()
    {
        $this->artisan('make:repository', ['name' => 'User'])
            ->expectsOutput('Creating repository for: User')
            ->expectsOutput('Repository created successfully!')
            ->assertExitCode(0);
    }

    public function test_make_service_command()
    {
        $this->artisan('make:service', ['name' => 'User'])
            ->expectsOutput('Creating service: User')
            ->expectsOutput('Service created successfully!')
            ->assertExitCode(0);
    }

    public function test_make_ddd_command()
    {
        $this->artisan('make:ddd', ['module' => 'UserManagement'])
            ->expectsOutput('Creating DDD module: UserManagement')
            ->expectsOutput('DDD module created successfully!')
            ->assertExitCode(0);
    }

    public function test_make_repository_command_with_force_option()
    {
        $this->artisan('make:repository', [
            'name' => 'User',
            '--force' => true
        ])
        ->expectsOutput('Creating repository for: User')
        ->expectsOutput('Repository created successfully!')
        ->assertExitCode(0);
    }

    public function test_make_repository_command_with_model_option()
    {
        $this->artisan('make:repository', [
            'name' => 'User',
            '--model' => 'App\\Models\\User'
        ])
        ->expectsOutput('Creating repository for: User')
        ->expectsOutput('Repository created successfully!')
        ->assertExitCode(0);
    }

    protected function tearDown(): void
    {
        // Clean up created files
        $filesystem = new Filesystem();
        $filesystem->deleteDirectory(app_path('Repositories'));
        $filesystem->deleteDirectory(app_path('Services'));
        $filesystem->deleteDirectory(app_path('Domain'));
        
        parent::tearDown();
    }
} 