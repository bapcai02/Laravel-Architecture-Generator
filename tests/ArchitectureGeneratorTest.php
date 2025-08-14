<?php

namespace LaravelArchitex\Tests;

use LaravelArchitex\Services\ArchitectureGenerator;
use LaravelArchitex\Services\TemplateEngine;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class ArchitectureGeneratorTest extends TestCase
{
    protected ArchitectureGenerator $generator;
    protected Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        Config::set('architex', [
            'patterns' => [
                'repository' => [
                    'enabled' => true,
                    'path' => 'app/Repositories',
                    'namespace' => 'App\\Repositories',
                    'interface_suffix' => 'RepositoryInterface',
                    'implementation_suffix' => 'Repository',
                ],
                'service' => [
                    'enabled' => true,
                    'path' => 'app/Services',
                    'namespace' => 'App\\Services',
                    'suffix' => 'Service',
                ],
                'ddd' => [
                    'enabled' => true,
                    'layers' => [
                        'domain' => [
                            'path' => 'app/Domain',
                            'namespaces' => [
                                'entities' => 'App\\Domain\\Entities',
                                'repositories' => 'App\\Domain\\Repositories',
                            ],
                        ],
                    ],
                ],
            ],
            'naming' => [
                'case' => 'pascal',
                'separator' => '_',
                'pluralize' => true,
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
        
        $this->filesystem = new Filesystem();
        $templateEngine = new TemplateEngine($this->filesystem);
        $this->generator = new ArchitectureGenerator($this->filesystem, $templateEngine);
    }

    public function test_can_generate_repository()
    {
        $name = 'User';
        $createdFiles = $this->generator->generateRepository($name);

        $this->assertIsArray($createdFiles);
        $this->assertCount(2, $createdFiles);
        
        // Check if files were created
        foreach ($createdFiles as $file) {
            $this->assertTrue($this->filesystem->exists($file));
            
            // Check file content
            $content = $this->filesystem->get($file);
            $this->assertStringContainsString('User', $content);
        }
    }

    public function test_can_generate_service()
    {
        $name = 'User';
        $createdFiles = $this->generator->generateService($name);

        $this->assertIsArray($createdFiles);
        $this->assertCount(1, $createdFiles);
        
        // Check if file was created
        $this->assertTrue($this->filesystem->exists($createdFiles[0]));
        
        // Check file content
        $content = $this->filesystem->get($createdFiles[0]);
        $this->assertStringContainsString('UserService', $content);
    }

    public function test_can_generate_ddd_structure()
    {
        $moduleName = 'UserManagement';
        $createdFiles = $this->generator->generateDDD($moduleName);

        $this->assertIsArray($createdFiles);
        $this->assertGreaterThan(0, count($createdFiles));
        
        // Check if files were created
        foreach ($createdFiles as $file) {
            $this->assertTrue($this->filesystem->exists($file));
            
            // Check file content
            $content = $this->filesystem->get($file);
            $this->assertStringContainsString('UserManagement', $content);
        }
    }

    public function test_can_generate_cqrs_structure()
    {
        $name = 'CreateUser';
        $createdFiles = $this->generator->generateCQRS($name);

        $this->assertIsArray($createdFiles);
        $this->assertCount(4, $createdFiles); // Command, Query, CommandHandler, QueryHandler
        
        // Check if files were created
        foreach ($createdFiles as $file) {
            $this->assertTrue($this->filesystem->exists($file));
        }
    }

    public function test_can_generate_event_structure()
    {
        $name = 'UserCreated';
        $createdFiles = $this->generator->generateEvent($name);

        $this->assertIsArray($createdFiles);
        $this->assertCount(2, $createdFiles); // Event and Listener
        
        // Check if files were created
        foreach ($createdFiles as $file) {
            $this->assertTrue($this->filesystem->exists($file));
        }
    }

    public function test_format_name_with_suffix()
    {
        $reflection = new \ReflectionClass($this->generator);
        $method = $reflection->getMethod('formatName');
        $method->setAccessible(true);

        $result = $method->invoke($this->generator, 'user', 'Repository');
        $this->assertEquals('UserRepository', $result);
    }

    protected function tearDown(): void
    {
        // Clean up created files
        if (isset($this->filesystem)) {
            $this->filesystem->deleteDirectory(app_path('Repositories'));
            $this->filesystem->deleteDirectory(app_path('Services'));
            $this->filesystem->deleteDirectory(app_path('Domain'));
            $this->filesystem->deleteDirectory(app_path('Application'));
            $this->filesystem->deleteDirectory(app_path('Infrastructure'));
            $this->filesystem->deleteDirectory(app_path('UI'));
            $this->filesystem->deleteDirectory(app_path('Commands'));
            $this->filesystem->deleteDirectory(app_path('Queries'));
            $this->filesystem->deleteDirectory(app_path('Handlers'));
            $this->filesystem->deleteDirectory(app_path('Events'));
            $this->filesystem->deleteDirectory(app_path('Listeners'));
        }
        
        parent::tearDown();
    }
} 