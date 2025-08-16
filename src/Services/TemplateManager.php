<?php

namespace LaravelArchitex\Services;

use Illuminate\Filesystem\Filesystem;

class TemplateManager
{
    protected Filesystem $filesystem;
    protected SimpleTemplateEngine $templateEngine;
    protected array $config;
    protected array $templateRegistry = [];

    public function __construct(Filesystem $filesystem, SimpleTemplateEngine $templateEngine)
    {
        $this->filesystem = $filesystem;
        $this->templateEngine = $templateEngine;
        $this->config = config('architex.templates', []);
        $this->registerDefaultTemplates();
    }

    /**
     * Register default templates
     */
    protected function registerDefaultTemplates(): void
    {
        $this->templateRegistry = [
            'repository' => [
                'interface' => 'repository/interfaces/repository-interface.stub',
                'implementation' => 'repository/implementations/repository-implementation.stub',
                'base' => 'repository/base/base-repository.stub',
                'controller' => 'repository/base/base-controller.stub',
            ],
            'service' => [
                'base' => 'service/base-service.stub',
            ],
            'cqrs' => [
                'command' => 'cqrs/commands/command.stub',
                'query' => 'cqrs/queries/query.stub',
                'command_handler' => 'cqrs/handlers/command-handler.stub',
                'query_handler' => 'cqrs/handlers/query-handler.stub',
            ],
            'event_bus' => [
                'event' => 'event-bus/events/event.stub',
                'listener' => 'event-bus/listeners/listener.stub',
            ],
            'hexagonal' => [
                'entity' => 'hexagonal/domain/entities/hexagonal-domain-entity.stub',
                'repository_port' => 'hexagonal/domain/ports/hexagonal-domain-repository-port.stub',
                'service_port' => 'hexagonal/domain/ports/hexagonal-domain-service-port.stub',
                'application_service' => 'hexagonal/application/hexagonal-application-service.stub',
                'repository_adapter' => 'hexagonal/infrastructure/adapters/hexagonal-infrastructure-repository-adapter.stub',
                'controller_adapter' => 'hexagonal/ui/adapters/hexagonal-ui-controller-adapter.stub',
            ],
            'modular' => [
                'controller' => 'modular/controllers/modular-controller.stub',
                'model' => 'modular/models/modular-model.stub',
                'service' => 'modular/services/modular-service.stub',
                'repository' => 'modular/repositories/modular-repository.stub',
                'provider' => 'modular/providers/modular-service-provider.stub',
                'routes' => 'modular/routes/modular-routes.stub',
                'config' => 'modular/config/modular-config.stub',
                'migration' => 'modular/database/migrations/modular-migration.stub',
                'seeder' => 'modular/database/seeders/modular-seeder.stub',
                'test' => 'modular/tests/modular-test.stub',
            ],
        ];
    }

    /**
     * Get template by type and name
     */
    public function getTemplate(string $type, string $name): string
    {
        if (!isset($this->templateRegistry[$type][$name])) {
            throw new \InvalidArgumentException("Template not found: {$type}.{$name}");
        }

        return $this->templateRegistry[$type][$name];
    }

    /**
     * Render template with variables
     */
    public function render(string $type, string $name, array $variables = []): string
    {
        $templatePath = $this->getTemplate($type, $name);
        return $this->templateEngine->render($templatePath, $variables);
    }

    /**
     * Register custom template
     */
    public function registerTemplate(string $type, string $name, string $path): void
    {
        if (!isset($this->templateRegistry[$type])) {
            $this->templateRegistry[$type] = [];
        }

        $this->templateRegistry[$type][$name] = $path;
    }

    /**
     * Get all available template types
     */
    public function getAvailableTypes(): array
    {
        return array_keys($this->templateRegistry);
    }

    /**
     * Get all templates for a specific type
     */
    public function getTemplatesForType(string $type): array
    {
        if (!isset($this->templateRegistry[$type])) {
            return [];
        }

        return array_keys($this->templateRegistry[$type]);
    }

    /**
     * Create custom template
     */
    public function createCustomTemplate(string $type, string $name, string $content): void
    {
        $customPath = $this->config['stub_path'] . '/custom';
        
        if (!$this->filesystem->exists($customPath)) {
            $this->filesystem->makeDirectory($customPath, 0755, true);
        }

        $templatePath = $customPath . '/' . $type . '-' . $name . '.stub';
        $this->filesystem->put($templatePath, $content);
        
        $this->registerTemplate($type, $name, 'custom/' . $type . '-' . $name . '.stub');
    }

    /**
     * Validate template variables
     */
    public function validateTemplate(string $type, string $name, array $variables): array
    {
        $templatePath = $this->getTemplate($type, $name);
        $content = $this->filesystem->get($this->config['default_stub_path'] . '/' . $templatePath);
        
        // Extract all variables from template
        preg_match_all('/{{([^}]+)}}/', $content, $matches);
        $requiredVariables = array_unique($matches[1]);
        
        $missingVariables = [];
        foreach ($requiredVariables as $variable) {
            $variable = trim($variable);
            if (!isset($variables[$variable])) {
                $missingVariables[] = $variable;
            }
        }
        
        return $missingVariables;
    }

    /**
     * Get template metadata
     */
    public function getTemplateMetadata(string $type, string $name): array
    {
        $templatePath = $this->getTemplate($type, $name);
        $content = $this->filesystem->get($this->config['default_stub_path'] . '/' . $templatePath);
        
        return [
            'type' => $type,
            'name' => $name,
            'path' => $templatePath,
            'size' => strlen($content),
            'variables' => $this->extractVariables($content),
        ];
    }

    /**
     * Extract variables from template content
     */
    protected function extractVariables(string $content): array
    {
        preg_match_all('/{{([^}]+)}}/', $content, $matches);
        return array_unique(array_map('trim', $matches[1]));
    }
} 