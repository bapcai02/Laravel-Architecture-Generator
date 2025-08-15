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
        
        // Create BaseRepository if it doesn't exist
        $baseNamespace = $config['base_namespace'] ?? $config['namespace'] . '\\Base';
        $basePath = $config['path'] . '/Base/BaseRepository.php';
        
        if (!$this->filesystem->exists($basePath)) {
            $baseContent = $this->templateEngine->render('repository/base/base-repository.stub', [
                'namespace' => $baseNamespace,
                'base_namespace' => $baseNamespace,
            ]);
            $this->createFile($basePath, $baseContent);
            $createdFiles[] = $basePath;
        }
        
        // Create BaseController if it doesn't exist
        $baseControllerPath = $config['path'] . '/Base/BaseController.php';
        
        if (!$this->filesystem->exists($baseControllerPath)) {
            $baseControllerContent = $this->templateEngine->render('repository/base/base-controller.stub', [
                'namespace' => $baseNamespace,
            ]);
            $this->createFile($baseControllerPath, $baseControllerContent);
            $createdFiles[] = $baseControllerPath;
        }
        
        // Create interface
        $interfaceContent = $this->templateEngine->render('repository/interfaces/repository-interface.stub', [
            'namespace' => $interfaceNamespace,
            'class_name' => $interfaceName,
            'base_namespace' => $baseNamespace,
            'model_name' => $name,
            'model_namespace' => 'App\\Models',
        ]);
        
        $this->createFile($interfacePath, $interfaceContent);
        $createdFiles[] = $interfacePath;
        
        // Create implementation
        $implementationContent = $this->templateEngine->render('repository/implementations/repository-implementation.stub', [
            'namespace' => $implementationNamespace,
            'class_name' => $implementationName,
            'interface_name' => $interfaceName,
            'interface_namespace' => $interfaceNamespace,
            'model_name' => $name,
            'model_namespace' => 'App\\Models',
            'base_namespace' => $baseNamespace,
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
        
        $serviceContent = $this->templateEngine->render('service/base-service.stub', [
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
        $commandContent = $this->templateEngine->render('cqrs/commands/command.stub', [
            'namespace' => $config['commands']['namespace'],
            'class_name' => $commandName,
            'model_name' => $name,
        ]);
        $this->createFile($commandPath, $commandContent);
        $createdFiles[] = $commandPath;
        
        // Create Query
        $queryPath = $config['queries']['path'] . '/' . $queryName . '.php';
        $queryContent = $this->templateEngine->render('cqrs/queries/query.stub', [
            'namespace' => $config['queries']['namespace'],
            'class_name' => $queryName,
            'model_name' => $name,
        ]);
        $this->createFile($queryPath, $queryContent);
        $createdFiles[] = $queryPath;
        
        // Create Command Handler
        $commandHandlerPath = $config['handlers']['path'] . '/' . $commandHandlerName . '.php';
        $commandHandlerContent = $this->templateEngine->render('cqrs/handlers/command-handler.stub', [
            'namespace' => $config['handlers']['namespace'],
            'class_name' => $commandHandlerName,
            'command_name' => $commandName,
            'command_namespace' => $config['commands']['namespace'],
            'command_class' => $commandName,
            'model_name' => $name,
        ]);
        $this->createFile($commandHandlerPath, $commandHandlerContent);
        $createdFiles[] = $commandHandlerPath;
        
        // Create Query Handler
        $queryHandlerPath = $config['handlers']['path'] . '/' . $queryHandlerName . '.php';
        $queryHandlerContent = $this->templateEngine->render('cqrs/handlers/query-handler.stub', [
            'namespace' => $config['handlers']['namespace'],
            'class_name' => $queryHandlerName,
            'query_name' => $queryName,
            'query_namespace' => $config['queries']['namespace'],
            'query_class' => $queryName,
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
                
                $sampleContent = $this->templateEngine->render('ddd/ddd-sample.stub', [
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
        $eventContent = $this->templateEngine->render('event-bus/events/event.stub', [
            'namespace' => $config['events']['namespace'],
            'class_name' => $eventName,
            'model_name' => $name,
        ]);
        $this->createFile($eventPath, $eventContent);
        $createdFiles[] = $eventPath;
        
        // Create Listener
        $listenerContent = $this->templateEngine->render('event-bus/listeners/listener.stub', [
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
     * Generate modular/package-based architecture structure
     */
    public function generateModular(string $name, array $options = []): array
    {
        $moduleName = Str::studly($name);
        $basePath = $options['path'] ?? 'app/Modules/' . $moduleName;
        $namespace = $options['namespace'] ?? 'App\\Modules\\' . $moduleName;
        
        $createdFiles = [];
        
        // Create main module directory structure
        $directories = [
            'Controllers',
            'Models',
            'Services',
            'Repositories',
            'Providers',
            'Routes',
            'Config',
            'Views',
            'Assets',
            'Database/Migrations',
            'Database/Seeders',
            'Tests',
        ];
        
        foreach ($directories as $dir) {
            $dirPath = $basePath . '/' . $dir;
            $this->filesystem->makeDirectory($dirPath, 0755, true, true);
        }
        
        // Generate Module Service Provider
        $providerName = $moduleName . 'ServiceProvider';
        $providerPath = $basePath . '/Providers/' . $providerName . '.php';
        $providerContent = $this->templateEngine->render('modular/providers/modular-service-provider.stub', [
            'namespace' => $namespace . '\\Providers',
            'class_name' => $providerName,
            'module_name' => $moduleName,
            'module_namespace' => $namespace,
            'strtolower(class_name)' => strtolower($moduleName),
            'with_routes' => $options['with_routes'] ?? false,
            'with_views' => $options['with_views'] ?? false,
            'with_assets' => $options['with_assets'] ?? false,
        ]);
        $this->createFile($providerPath, $providerContent);
        $createdFiles[] = $providerPath;
        
        // Generate Module Routes
        if ($options['with_routes'] ?? false) {
            $routesPath = $basePath . '/Routes/web.php';
            $routesContent = $this->templateEngine->render('modular/routes/modular-routes.stub', [
                'module_name' => $moduleName,
                'module_namespace' => $namespace,
                'strtolower(class_name)' => strtolower($moduleName),
            ]);
            $this->createFile($routesPath, $routesContent);
            $createdFiles[] = $routesPath;
        }
        
        // Generate Module Config
        if ($options['with_config'] ?? false) {
            $configPath = $basePath . '/Config/' . strtolower($moduleName) . '.php';
            $configContent = $this->templateEngine->render('modular/config/modular-config.stub', [
                'module_name' => $moduleName,
                'module_namespace' => $namespace,
                'strtolower(class_name)' => strtolower($moduleName),
                'strtoupper($module_name)' => strtoupper($moduleName),
            ]);
            $this->createFile($configPath, $configContent);
            $createdFiles[] = $configPath;
        }
        
        // Generate Base Controller
        $controllerName = $moduleName . 'Controller';
        $controllerPath = $basePath . '/Controllers/' . $controllerName . '.php';
        $controllerContent = $this->templateEngine->render('modular/controllers/modular-controller.stub', [
            'namespace' => $namespace . '\\Controllers',
            'class_name' => $controllerName,
            'module_name' => $moduleName,
            'module_namespace' => $namespace,
            'strtolower(class_name)' => strtolower($moduleName),
        ]);
        $this->createFile($controllerPath, $controllerContent);
        $createdFiles[] = $controllerPath;
        
        // Generate Base Model
        $modelName = $moduleName;
        $modelPath = $basePath . '/Models/' . $modelName . '.php';
        $modelContent = $this->templateEngine->render('modular/models/modular-model.stub', [
            'namespace' => $namespace . '\\Models',
            'class_name' => $modelName,
            'module_name' => $moduleName,
            'module_namespace' => $namespace,
            'table_name' => strtolower(Str::plural($moduleName)),
        ]);
        $this->createFile($modelPath, $modelContent);
        $createdFiles[] = $modelPath;
        
        // Generate Base Service
        $serviceName = $moduleName . 'Service';
        $servicePath = $basePath . '/Services/' . $serviceName . '.php';
        $serviceContent = $this->templateEngine->render('modular/services/modular-service.stub', [
            'namespace' => $namespace . '\\Services',
            'class_name' => $serviceName,
            'module_name' => $moduleName,
            'module_namespace' => $namespace,
            'model_namespace' => $namespace . '\\Models',
            'strtolower(class_name)' => strtolower($moduleName),
        ]);
        $this->createFile($servicePath, $serviceContent);
        $createdFiles[] = $servicePath;
        
        // Generate Base Repository
        $repositoryName = $moduleName . 'Repository';
        $repositoryPath = $basePath . '/Repositories/' . $repositoryName . '.php';
        $repositoryContent = $this->templateEngine->render('modular/repositories/modular-repository.stub', [
            'namespace' => $namespace . '\\Repositories',
            'class_name' => $repositoryName,
            'module_name' => $moduleName,
            'module_namespace' => $namespace,
            'model_namespace' => $namespace . '\\Models',
            'strtolower(class_name)' => strtolower($moduleName),
        ]);
        $this->createFile($repositoryPath, $repositoryContent);
        $createdFiles[] = $repositoryPath;
        
        // Generate Migration
        if ($options['with_migrations'] ?? false) {
            $migrationName = 'create_' . strtolower(Str::plural($moduleName)) . '_table';
            $migrationPath = $basePath . '/Database/Migrations/' . date('Y_m_d_His') . '_' . $migrationName . '.php';
            $migrationContent = $this->templateEngine->render('modular/database/migrations/modular-migration.stub', [
                'table_name' => strtolower(Str::plural($moduleName)),
                'class_name' => 'Create' . Str::plural($moduleName) . 'Table',
            ]);
            $this->createFile($migrationPath, $migrationContent);
            $createdFiles[] = $migrationPath;
        }
        
        // Generate Seeder
        if ($options['with_seeders'] ?? false) {
            $seederName = $moduleName . 'Seeder';
            $seederPath = $basePath . '/Database/Seeders/' . $seederName . '.php';
            $seederContent = $this->templateEngine->render('modular/database/seeders/modular-seeder.stub', [
                'namespace' => $namespace . '\\Database\\Seeders',
                'class_name' => $seederName,
                'module_name' => $moduleName,
                'module_namespace' => $namespace,
                'model_namespace' => $namespace . '\\Models',
                'strtolower(class_name)' => strtolower($moduleName),
            ]);
            $this->createFile($seederPath, $seederContent);
            $createdFiles[] = $seederPath;
        }
        
        // Generate Tests
        if ($options['with_tests'] ?? false) {
            $testName = $moduleName . 'Test';
            $testPath = $basePath . '/Tests/' . $testName . '.php';
            $testContent = $this->templateEngine->render('modular/tests/modular-test.stub', [
                'namespace' => $namespace . '\\Tests',
                'class_name' => $testName,
                'module_name' => $moduleName,
                'module_namespace' => $namespace,
                'strtolower(class_name)' => strtolower($moduleName),
            ]);
            $this->createFile($testPath, $testContent);
            $createdFiles[] = $testPath;
        }
        
        // Generate Module README
        $readmePath = $basePath . '/README.md';
                    $readmeContent = $this->templateEngine->render('modular/modular-readme.stub', [
            'module_name' => $moduleName,
            'module_namespace' => $namespace,
            'strtolower(class_name)' => strtolower($moduleName),
        ]);
        $this->createFile($readmePath, $readmeContent);
        $createdFiles[] = $readmePath;
        
        return $createdFiles;
    }

    /**
     * Generate Hexagonal Architecture (Ports and Adapters) structure
     */
    public function generateHexagonal(string $name, array $options = []): array
    {
        $basePath = $options['path'] ?? 'app/Hexagonal/' . $name;
        $namespace = $options['namespace'] ?? 'App\\Hexagonal\\' . $name;
        
        $createdFiles = [];
        
        // Create base directory structure
        $this->filesystem->makeDirectory($basePath, 0755, true, true);
        
        // Domain Layer
        $domainPath = $basePath . '/Domain';
        $this->filesystem->makeDirectory($domainPath . '/Entities', 0755, true, true);
        $this->filesystem->makeDirectory($domainPath . '/Ports', 0755, true, true);
        
        // Application Layer
        $applicationPath = $basePath . '/Application';
        $this->filesystem->makeDirectory($applicationPath . '/Services', 0755, true, true);
        
        // Infrastructure Layer
        $infrastructurePath = $basePath . '/Infrastructure';
        $this->filesystem->makeDirectory($infrastructurePath . '/Adapters', 0755, true, true);
        $this->filesystem->makeDirectory($infrastructurePath . '/database/migrations', 0755, true, true);
        
        // UI Layer
        $uiPath = $basePath . '/UI';
        $this->filesystem->makeDirectory($uiPath . '/Adapters', 0755, true, true);
        $this->filesystem->makeDirectory($uiPath . '/routes', 0755, true, true);
        
        // Tests
        if ($options['with_tests'] ?? false) {
            $this->filesystem->makeDirectory($basePath . '/Tests', 0755, true, true);
        }
        
        // Generate Domain Entity
        $entityName = $name;
        $entityPath = $domainPath . '/Entities/' . $entityName . '.php';
        $entityContent = $this->templateEngine->render('hexagonal/domain/entities/hexagonal-domain-entity.stub', [
            'namespace' => $namespace,
            'class_name' => $entityName,
        ]);
        $this->createFile($entityPath, $entityContent);
        $createdFiles[] = $entityPath;
        
        // Generate Repository Port
        $repositoryPortName = $name . 'RepositoryPort';
        $repositoryPortPath = $domainPath . '/Ports/' . $repositoryPortName . '.php';
        $repositoryPortContent = $this->templateEngine->render('hexagonal/domain/ports/hexagonal-domain-repository-port.stub', [
            'namespace' => $namespace . '\\Domain\\Ports',
            'class_name' => $name,
            'strtolower(class_name)' => strtolower($name),
        ]);
        $this->createFile($repositoryPortPath, $repositoryPortContent);
        $createdFiles[] = $repositoryPortPath;
        
        // Generate Service Port
        $servicePortName = $name . 'ServicePort';
        $servicePortPath = $domainPath . '/Ports/' . $servicePortName . '.php';
        $servicePortContent = $this->templateEngine->render('hexagonal/domain/ports/hexagonal-domain-service-port.stub', [
            'namespace' => $namespace . '\\Domain\\Ports',
            'class_name' => $name,
            'strtolower(class_name)' => strtolower($name),
        ]);
        $this->createFile($servicePortPath, $servicePortContent);
        $createdFiles[] = $servicePortPath;
        
        // Generate Application Service
        $applicationServiceName = $name . 'ApplicationService';
        $applicationServicePath = $applicationPath . '/Services/' . $applicationServiceName . '.php';
        $applicationServiceContent = $this->templateEngine->render('hexagonal/application/hexagonal-application-service.stub', [
            'namespace' => $namespace . '\\Application\\Services',
            'class_name' => $name,
            'strtolower(class_name)' => strtolower($name),
        ]);
        $this->createFile($applicationServicePath, $applicationServiceContent);
        $createdFiles[] = $applicationServicePath;
        
        // Generate Infrastructure Repository Adapter
        $repositoryAdapterName = $name . 'RepositoryAdapter';
        $repositoryAdapterPath = $infrastructurePath . '/Adapters/' . $repositoryAdapterName . '.php';
        $repositoryAdapterContent = $this->templateEngine->render('hexagonal/infrastructure/adapters/hexagonal-infrastructure-repository-adapter.stub', [
            'namespace' => $namespace . '\\Infrastructure\\Adapters',
            'class_name' => $name,
            'strtolower(class_name)' => strtolower($name),
        ]);
        $this->createFile($repositoryAdapterPath, $repositoryAdapterContent);
        $createdFiles[] = $repositoryAdapterPath;
        
        // Generate UI Controller Adapter
        $controllerAdapterName = $name . 'ControllerAdapter';
        $controllerAdapterPath = $uiPath . '/Adapters/' . $controllerAdapterName . '.php';
        $controllerAdapterContent = $this->templateEngine->render('hexagonal/ui/adapters/hexagonal-ui-controller-adapter.stub', [
            'namespace' => $namespace . '\\UI\\Adapters',
            'class_name' => $name,
            'strtolower(class_name)' => strtolower($name),
        ]);
        $this->createFile($controllerAdapterPath, $controllerAdapterContent);
        $createdFiles[] = $controllerAdapterPath;
        
        // Generate Service Provider
        $serviceProviderName = $name . 'ServiceProvider';
        $serviceProviderPath = $basePath . '/' . $serviceProviderName . '.php';
        $serviceProviderContent = $this->templateEngine->render('hexagonal/shared/hexagonal-service-provider.stub', [
            'namespace' => $namespace,
            'class_name' => $name,
            'strtolower(class_name)' => strtolower($name),
        ]);
        $this->createFile($serviceProviderPath, $serviceProviderContent);
        $createdFiles[] = $serviceProviderPath;
        
        // Generate Routes
        if ($options['with_routes'] ?? false) {
            $routesPath = $uiPath . '/routes/' . strtolower($name) . '_routes.php';
            $routesContent = $this->templateEngine->render('hexagonal/ui/routes/hexagonal-routes.stub', [
                'namespace' => $namespace . '\\UI\\Adapters',
                'class_name' => $name,
                'strtolower(class_name)' => strtolower($name),
            ]);
            $this->createFile($routesPath, $routesContent);
            $createdFiles[] = $routesPath;
        }
        
        // Generate Migration
        if ($options['with_migrations'] ?? false) {
            $migrationName = 'create_' . strtolower(Str::plural($name)) . '_table';
            $migrationPath = $infrastructurePath . '/database/migrations/' . date('Y_m_d_His') . '_' . $migrationName . '.php';
            $migrationContent = $this->templateEngine->render('hexagonal/infrastructure/database/hexagonal-migration.stub', [
                'strtolower(class_name)' => strtolower($name),
            ]);
            $this->createFile($migrationPath, $migrationContent);
            $createdFiles[] = $migrationPath;
        }
        
        // Generate Tests
        if ($options['with_tests'] ?? false) {
            $testName = $name . 'HexagonalTest';
            $testPath = $basePath . '/Tests/' . $testName . '.php';
            $testContent = $this->templateEngine->render('hexagonal/shared/hexagonal-test.stub', [
                'namespace' => $namespace . '\\Tests',
                'class_name' => $name,
                'strtolower(class_name)' => strtolower($name),
            ]);
            $this->createFile($testPath, $testContent);
            $createdFiles[] = $testPath;
        }
        
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