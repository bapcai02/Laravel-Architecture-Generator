<?php

namespace LaravelArchitex\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class TemplateEngine
{
    protected Filesystem $filesystem;
    protected array $config;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->config = config('architex.templates', []);
    }

    /**
     * Render a stub template with variables
     */
    public function render(string $stubName, array $variables = []): string
    {
        $stubPath = $this->getStubPath($stubName);
        
        if (!$this->filesystem->exists($stubPath)) {
            throw new \InvalidArgumentException("Stub file not found: {$stubPath}");
        }
        
        $content = $this->filesystem->get($stubPath);
        
        // Merge with default variables
        $variables = array_merge($this->config['variables'] ?? [], $variables);
        
        return $this->replaceVariables($content, $variables);
    }

    /**
     * Get the full path to a stub file
     */
    protected function getStubPath(string $stubName): string
    {
        // First check custom stub path
        $customPath = $this->config['stub_path'] . '/' . $stubName;
        if ($this->filesystem->exists($customPath)) {
            return $customPath;
        }
        
        // Fall back to default stub path
        return $this->config['default_stub_path'] . '/' . $stubName;
    }

    /**
     * Replace variables in stub content
     */
    protected function replaceVariables(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            // Handle multiple formats: {{ variable }}, {{ variable }}, {{variable}}
            $placeholders = [
                '{{' . $key . '}}',
                '{{ ' . $key . ' }}',
                '{{' . $key . ' }}',
                '{{ ' . $key . '}}'
            ];
            
            foreach ($placeholders as $placeholder) {
                $content = str_replace($placeholder, $value, $content);
            }
        }
        
        // Handle Blade-like conditionals for modular templates
        $content = $this->processConditionals($content, $variables);
        
        return $content;
    }

    /**
     * Process conditional statements in templates
     */
    protected function processConditionals(string $content, array $variables): string
    {
        // Handle @if($variable) ... @endif patterns
        $content = preg_replace_callback('/@if\s*\(\s*([^)]+)\s*\)(.*?)@endif/s', function($matches) use ($variables) {
            $condition = trim($matches[1]);
            $body = $matches[2];
            
            // Simple condition evaluation
            if (isset($variables[$condition]) && $variables[$condition]) {
                return $body;
            }
            
            return '';
        }, $content);
        
        return $content;
    }

    /**
     * Get available stub files
     */
    public function getAvailableStubs(): array
    {
        $stubs = [];
        
        // Check custom stub path
        if (isset($this->config['stub_path']) && $this->filesystem->exists($this->config['stub_path'])) {
            $files = $this->filesystem->files($this->config['stub_path']);
            foreach ($files as $file) {
                $stubs[] = basename($file);
            }
        }
        
        // Check default stub path
        if (isset($this->config['default_stub_path']) && $this->filesystem->exists($this->config['default_stub_path'])) {
            $files = $this->filesystem->files($this->config['default_stub_path']);
            foreach ($files as $file) {
                $stubName = basename($file);
                if (!in_array($stubName, $stubs)) {
                    $stubs[] = $stubName;
                }
            }
        }
        
        return $stubs;
    }
} 