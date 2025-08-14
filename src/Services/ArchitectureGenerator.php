<?php

namespace LaravelArchitex\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ArchitectureGenerator
{
    protected Filesystem $filesystem;
    protected TemplateEngine $templateEngine;
    protected array $config;

    public function __construct(Filesystem $filesystem, TemplateEngine $templateEngine)
    {
        $this->filesystem = $filesystem;
        $this->templateEngine = $templateEngine;
        $this->config = config('architex', []);
    }

    /**
     * Generate repository pattern structure
     */
    public function generateRepository(string $name, array $options = []): array
    {
        $config = $this->config['patterns']['repository'];
        $naming = $this->config['naming'];
        
        $interfaceName = $this->formatName($name, $naming['suffixes']['repository_interface']);
        $implementationName = $this->formatName($name, $naming['suffixes']['repository']);
        
        $interfacePath = $config['path'] . '/Interfaces/' . $interfaceName . '.php';
        $implementationPath = $config['path'] . '/' . $implementationName . '.php';
        
        $interfaceNamespace = $config['namespace'] . '\\Interfaces';
        $implementationNamespace = $config['namespace'];
        
        $createdFiles = [];
        
        // Create interface
        $interfaceContent = $this->templateEngine->render('repository-interface.stub', [
            'namespace' => $interfaceNamespace,
            'interface_name' => $interfaceName,
            'model_name' => $name,
            'model_namespace' => 'App\\Models',
        ]);
        
        $this->createFile($interfacePath, $interfaceContent);
        $createdFiles[] = $interfacePath;
        
        // Create implementation
        $implementationContent = $this->templateEngine->render('repository-implementation.stub', [
            'namespace' => $implementationNamespace,
            'class_name' => $implementationName,
            'interface_name' => $interfaceName,
            'interface_namespace' => $interfaceNamespace,
            'model_name' => $name,
            'model_namespace' => 'App\\Models',
        ]);
        
        $this->createFile($implementationPath, $implementationContent);
        $createdFiles[] = $implementationPath;
        
        return $createdFiles;
    }

    /**
     * Generate service layer structure
     */
    public function generateService(string $name, array $options = []): array
    {
        $config = $this->config['patterns']['service'];
        $naming = $this->config['naming'];
        
        $serviceName = $this->formatName($name, $naming['suffixes']['service']);
        $servicePath = $config['path'] . '/' . $serviceName . '.php';
        $serviceNamespace = $config['namespace'];
        
        $serviceContent = $this->templateEngine->render('service.stub', [
            'namespace' => $serviceNamespace,
            'class_name' => $serviceName,
            'model_name' => $name,
            'model_namespace' => 'App\\Models',
        ]);
        
        $this->createFile($servicePath, $serviceContent);
        
        return [$servicePath];
    }

    /**
     * Generate CQRS structure
     */
    public function generateCQRS(string $name, array $options = []): array
    {
        $config = $this->config['patterns']['cqrs'];
        $naming = $this->config['naming'];
        
        $commandName = $this->formatName($name, $naming['suffixes']['command']);
        $queryName = $this->formatName($name, $naming['suffixes']['query']);
        $commandHandlerName = $this->formatName($name, $naming['suffixes']['handler']);
        $queryHandlerName = $this->formatName($name, $naming['suffixes']['handler']);
        
        $createdFiles = [];
        
        // Create Command
        $commandPath = $config['commands']['path'] . '/' . $commandName . '.php';
        $commandContent = $this->templateEngine->render('command.stub', [
            'namespace' => $config['commands']['namespace'],
            'class_name' => $commandName,
            'model_name' => $name,
        ]);
        $this->createFile($commandPath, $commandContent);
        $createdFiles[] = $commandPath;
        
        // Create Query
        $queryPath = $config['queries']['path'] . '/' . $queryName . '.php';
        $queryContent = $this->templateEngine->render('query.stub', [
            'namespace' => $config['queries']['namespace'],
            'class_name' => $queryName,
            'model_name' => $name,
        ]);
        $this->createFile($queryPath, $queryContent);
        $createdFiles[] = $queryPath;
        
        // Create Command Handler
        $commandHandlerPath = $config['handlers']['path'] . '/' . $commandHandlerName . '.php';
        $commandHandlerContent = $this->templateEngine->render('command-handler.stub', [
            'namespace' => $config['handlers']['namespace'],
            'class_name' => $commandHandlerName,
            'command_name' => $commandName,
            'command_namespace' => $config['commands']['namespace'],
            'model_name' => $name,
        ]);
        $this->createFile($commandHandlerPath, $commandHandlerContent);
        $createdFiles[] = $commandHandlerPath;
        
        // Create Query Handler
        $queryHandlerPath = $config['handlers']['path'] . '/' . $queryHandlerName . '.php';
        $queryHandlerContent = $this->templateEngine->render('query-handler.stub', [
            'namespace' => $config['handlers']['namespace'],
            'class_name' => $queryHandlerName,
            'query_name' => $queryName,
            'query_namespace' => $config['queries']['namespace'],
            'model_name' => $name,
        ]);
        $this->createFile($queryHandlerPath, $queryHandlerContent);
        $createdFiles[] = $queryHandlerPath;
        
        return $createdFiles;
    }

    /**
     * Generate DDD structure
     */
    public function generateDDD(string $moduleName, array $options = []): array
    {
        $config = $this->config['patterns']['ddd'];
        $createdFiles = [];
        
        foreach ($config['layers'] as $layerName => $layerConfig) {
            $layerPath = $layerConfig['path'] . '/' . $moduleName;
            
            // Create layer directory
            $this->filesystem->makeDirectory($layerPath, 0755, true, true);
            
            // Create subdirectories for each layer
            foreach ($layerConfig['namespaces'] as $subDir => $namespace) {
                $subPath = $layerPath . '/' . Str::studly($subDir);
                $this->filesystem->makeDirectory($subPath, 0755, true, true);
                
                // Create a sample file for each subdirectory
                $sampleFileName = $moduleName . Str::studly($subDir) . '.php';
                $sampleFilePath = $subPath . '/' . $sampleFileName;
                
                $sampleContent = $this->templateEngine->render('ddd-sample.stub', [
                    'namespace' => $namespace,
                    'class_name' => $moduleName . Str::studly($subDir),
                    'layer' => $layerName,
                    'sub_directory' => $subDir,
                ]);
                
                $this->createFile($sampleFilePath, $sampleContent);
                $createdFiles[] = $sampleFilePath;
            }
        }
        
        return $createdFiles;
    }

    /**
     * Generate event bus structure
     */
    public function generateEvent(string $name, array $options = []): array
    {
        $config = $this->config['patterns']['event_bus'];
        $naming = $this->config['naming'];
        
        $eventName = $this->formatName($name, $naming['suffixes']['event']);
        $listenerName = $this->formatName($name, $naming['suffixes']['listener']);
        
        $eventPath = $config['events']['path'] . '/' . $eventName . '.php';
        $listenerPath = $config['listeners']['path'] . '/' . $listenerName . '.php';
        
        $createdFiles = [];
        
        // Create Event
        $eventContent = $this->templateEngine->render('event.stub', [
            'namespace' => $config['events']['namespace'],
            'class_name' => $eventName,
            'model_name' => $name,
        ]);
        $this->createFile($eventPath, $eventContent);
        $createdFiles[] = $eventPath;
        
        // Create Listener
        $listenerContent = $this->templateEngine->render('listener.stub', [
            'namespace' => $config['listeners']['namespace'],
            'class_name' => $listenerName,
            'event_name' => $eventName,
            'event_namespace' => $config['events']['namespace'],
            'model_name' => $name,
        ]);
        $this->createFile($listenerPath, $listenerContent);
        $createdFiles[] = $listenerPath;
        
        return $createdFiles;
    }

    /**
     * Format name according to naming conventions
     */
    protected function formatName(string $name, string $suffix = ''): string
    {
        $formattedName = Str::studly($name);
        
        if ($suffix) {
            $formattedName .= $suffix;
        }
        
        return $formattedName;
    }

    /**
     * Create file with content
     */
    protected function createFile(string $path, string $content): void
    {
        $directory = dirname($path);
        
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true, true);
        }
        
        $this->filesystem->put($path, $content);
    }
} 