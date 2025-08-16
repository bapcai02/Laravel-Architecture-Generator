<?php

namespace LaravelArchitex\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class AdvancedTemplateEngine
{
    protected Filesystem $filesystem;
    protected array $config;
    protected array $baseTemplates = [];

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->config = config('architex.templates', []);
        $this->loadBaseTemplates();
    }

    /**
     * Load base templates for inheritance
     */
    protected function loadBaseTemplates(): void
    {
        $basePath = $this->config['default_stub_path'] . '/architex/base';
        
        if ($this->filesystem->exists($basePath)) {
            $files = $this->filesystem->files($basePath);
            foreach ($files as $file) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $this->baseTemplates[$name] = $this->filesystem->get($file);
            }
        }
    }

    /**
     * Render template with advanced features
     */
    public function render(string $templateName, array $variables = []): string
    {
        $content = $this->getTemplateContent($templateName);
        
        // Merge with default variables
        $variables = array_merge($this->config['variables'] ?? [], $variables);
        
        // Process inheritance
        $content = $this->processInheritance($content, $variables);
        
        // Process conditionals and loops
        $content = $this->processAdvancedSyntax($content, $variables);
        
        // Replace simple variables
        $content = $this->replaceVariables($content, $variables);
        
        return $content;
    }

    /**
     * Get template content with inheritance support
     */
    protected function getTemplateContent(string $templateName): string
    {
        $templatePath = $this->getTemplatePath($templateName);
        
        if (!$this->filesystem->exists($templatePath)) {
            throw new \InvalidArgumentException("Template not found: {$templatePath}");
        }
        
        return $this->filesystem->get($templatePath);
    }

    /**
     * Process template inheritance
     */
    protected function processInheritance(string $content, array $variables): string
    {
        // Check for {{#extends "base-template"}} syntax
        if (preg_match('/{{#extends\s+"([^"]+)"}}/', $content, $matches)) {
            $baseTemplate = $matches[1];
            
            if (isset($this->baseTemplates[$baseTemplate])) {
                $baseContent = $this->baseTemplates[$baseTemplate];
                
                // Extract sections from current template
                preg_match_all('/{{#section\s+"([^"]+)"}}(.*?){{\/section}}/s', $content, $sectionMatches, PREG_SET_ORDER);
                
                $sections = [];
                foreach ($sectionMatches as $match) {
                    $sections[$match[1]] = $match[2];
                }
                
                // Replace sections in base template
                foreach ($sections as $sectionName => $sectionContent) {
                    $baseContent = str_replace("{{#yield \"{$sectionName}\"}}", $sectionContent, $baseContent);
                }
                
                return $baseContent;
            }
        }
        
        return $content;
    }

    /**
     * Process advanced syntax (conditionals, loops)
     */
    protected function processAdvancedSyntax(string $content, array $variables): string
    {
        // Handle {{#if}} conditionals
        $content = preg_replace_callback('/{{#if\s+([^}]+)}}(.*?){{\/if}}/s', function($matches) use ($variables) {
            $condition = trim($matches[1]);
            $body = $matches[2];
            
            if ($this->evaluateCondition($condition, $variables)) {
                return $this->replaceVariables($body, $variables);
            }
            
            return '';
        }, $content);
        
        // Handle {{#each}} loops
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
     * Create template with inheritance
     */
    public function createTemplate(string $name, string $baseTemplate, array $sections = []): string
    {
        $content = "{{#extends \"{$baseTemplate}\"}}\n\n";
        
        foreach ($sections as $sectionName => $sectionContent) {
            $content .= "{{#section \"{$sectionName}\"}}\n";
            $content .= $sectionContent . "\n";
            $content .= "{{/section}}\n\n";
        }
        
        return $content;
    }
} 