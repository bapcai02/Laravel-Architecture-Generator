<?php

namespace LaravelArchitex\Services;

use Illuminate\Filesystem\Filesystem;

class SimpleTemplateEngine
{
    protected Filesystem $filesystem;
    protected array $config;
    protected array $templateCache = [];

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->config = config('architex.templates', []);
    }

    /**
     * Render template with variables
     */
    public function render(string $templateName, array $variables = []): string
    {
        $content = $this->getTemplateContent($templateName);
        
        // Merge with default variables, but allow explicit variables to override
        $defaultVariables = $this->config['variables'] ?? [];
        $variables = array_merge($defaultVariables, $variables);
        
        // Process conditionals and loops
        $content = $this->processConditionals($content, $variables);
        $content = $this->processLoops($content, $variables);
        
        // Replace variables
        $content = $this->replaceVariables($content, $variables);
        
        return $content;
    }

    /**
     * Get template content with caching
     */
    protected function getTemplateContent(string $templateName): string
    {
        if (isset($this->templateCache[$templateName])) {
            return $this->templateCache[$templateName];
        }

        $templatePath = $this->getTemplatePath($templateName);
        
        if (!$this->filesystem->exists($templatePath)) {
            throw new \InvalidArgumentException("Template not found: {$templatePath}");
        }
        
        $content = $this->filesystem->get($templatePath);
        $this->templateCache[$templateName] = $content;
        
        return $content;
    }

    /**
     * Process conditional statements
     */
    protected function processConditionals(string $content, array $variables): string
    {
        // Handle {{#if variable}} ... {{/if}} patterns
        $content = preg_replace_callback('/{{#if\s+([^}]+)}}(.*?){{\/if}}/s', function($matches) use ($variables) {
            $condition = trim($matches[1]);
            $body = $matches[2];
            
            if ($this->evaluateCondition($condition, $variables)) {
                return $this->replaceVariables($body, $variables);
            }
            
            return '';
        }, $content);
        
        return $content;
    }

    /**
     * Process loop statements
     */
    protected function processLoops(string $content, array $variables): string
    {
        // Handle {{#each array}} ... {{/each}} patterns
        $content = preg_replace_callback('/{{#each\s+([^}]+)}}(.*?){{\/each}}/s', function($matches) use ($variables) {
            $arrayKey = trim($matches[1]);
            $body = $matches[2];
            
            if (isset($variables[$arrayKey]) && is_array($variables[$arrayKey])) {
                $result = '';
                foreach ($variables[$arrayKey] as $item) {
                    $itemVariables = array_merge($variables, ['item' => $item]);
                    $result .= $this->replaceVariables($body, $itemVariables);
                }
                return $result;
            }
            
            return '';
        }, $content);
        
        return $content;
    }

    /**
     * Evaluate conditional expression
     */
    protected function evaluateCondition(string $condition, array $variables): bool
    {
        // Simple condition evaluation
        if (isset($variables[$condition])) {
            return (bool) $variables[$condition];
        }
        
        // Handle negated conditions
        if (strpos($condition, '!') === 0) {
            $key = substr($condition, 1);
            return !isset($variables[$key]) || !$variables[$key];
        }
        
        return false;
    }

    /**
     * Replace variables in content
     */
    protected function replaceVariables(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            // Use preg_replace to ensure we only replace each placeholder once
            $content = preg_replace('/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/', $value, $content);
        }
        
        return $content;
    }

    /**
     * Get template path
     */
    protected function getTemplatePath(string $templateName): string
    {
        // Check custom template path
        $customPath = $this->config['stub_path'] . '/' . $templateName;
        if ($this->filesystem->exists($customPath)) {
            return $customPath;
        }
        
        // Fall back to default template path
        return $this->config['default_stub_path'] . '/' . $templateName;
    }

    /**
     * Create template from base template
     */
    public function createFromBase(string $baseTemplate, array $sections = [], array $variables = []): string
    {
        $baseContent = $this->getTemplateContent($baseTemplate);
        
        // Replace sections
        foreach ($sections as $sectionName => $sectionContent) {
            $baseContent = str_replace("{{#section \"{$sectionName}\"}}", $sectionContent, $baseContent);
        }
        
        // Process variables
        $variables = array_merge($this->config['variables'] ?? [], $variables);
        $baseContent = $this->processConditionals($baseContent, $variables);
        $baseContent = $this->processLoops($baseContent, $variables);
        $baseContent = $this->replaceVariables($baseContent, $variables);
        
        return $baseContent;
    }

    /**
     * Clear template cache
     */
    public function clearCache(): void
    {
        $this->templateCache = [];
    }
} 