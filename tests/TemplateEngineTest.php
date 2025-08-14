<?php

namespace LaravelArchitex\Tests;

use LaravelArchitex\Services\TemplateEngine;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class TemplateEngineTest extends TestCase
{
    protected TemplateEngine $templateEngine;
    protected Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        Config::set('architex.templates', [
            'stub_path' => base_path('stubs/architex'),
            'default_stub_path' => __DIR__ . '/../stubs',
            'variables' => [
                'app_namespace' => 'App',
                'author' => 'Laravel Architex',
                'year' => date('Y'),
            ],
        ]);
        
        $this->filesystem = new Filesystem();
        $this->templateEngine = new TemplateEngine($this->filesystem);
    }

    public function test_can_render_template_with_variables()
    {
        // Create a test stub file
        $stubContent = '<?php namespace {{namespace}}; class {{class_name}} {}';
        $stubPath = __DIR__ . '/../stubs/test-template.stub';
        $this->filesystem->put($stubPath, $stubContent);

        $variables = [
            'namespace' => 'App\\Test',
            'class_name' => 'TestClass',
        ];

        $result = $this->templateEngine->render('test-template.stub', $variables);

        $expected = '<?php namespace App\\Test; class TestClass {}';
        $this->assertEquals($expected, $result);

        // Clean up
        $this->filesystem->delete($stubPath);
    }

    public function test_can_merge_default_variables()
    {
        // Create a test stub file
        $stubContent = 'Author: {{author}}, Year: {{year}}, Class: {{class_name}}';
        $stubPath = __DIR__ . '/../stubs/test-merge.stub';
        $this->filesystem->put($stubPath, $stubContent);

        $variables = [
            'class_name' => 'TestClass',
        ];

        $result = $this->templateEngine->render('test-merge.stub', $variables);

        $this->assertStringContainsString('Author: Laravel Architex', $result);
        $this->assertStringContainsString('Year: ' . date('Y'), $result);
        $this->assertStringContainsString('Class: TestClass', $result);

        // Clean up
        $this->filesystem->delete($stubPath);
    }

    public function test_throws_exception_for_missing_stub()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Stub file not found:');

        $this->templateEngine->render('non-existent.stub', []);
    }

    public function test_can_get_available_stubs()
    {
        $stubs = $this->templateEngine->getAvailableStubs();
        
        $this->assertIsArray($stubs);
        $this->assertContains('repository-interface.stub', $stubs);
        $this->assertContains('service.stub', $stubs);
    }
} 