<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Architecture Patterns
    |--------------------------------------------------------------------------
    |
    | Supported architecture patterns and their configurations
    |
    */
    'patterns' => [
        'ddd' => [
            'enabled' => true,
            'layers' => [
                'domain' => [
                    'path' => 'app/Domain',
                    'namespaces' => [
                        'entities' => 'App\\Domain\\Entities',
                        'repositories' => 'App\\Domain\\Repositories',
                        'services' => 'App\\Domain\\Services',
                        'events' => 'App\\Domain\\Events',
                        'exceptions' => 'App\\Domain\\Exceptions',
                    ],
                ],
                'application' => [
                    'path' => 'app/Application',
                    'namespaces' => [
                        'services' => 'App\\Application\\Services',
                        'commands' => 'App\\Application\\Commands',
                        'queries' => 'App\\Application\\Queries',
                        'handlers' => 'App\\Application\\Handlers',
                    ],
                ],
                'infrastructure' => [
                    'path' => 'app/Infrastructure',
                    'namespaces' => [
                        'repositories' => 'App\\Infrastructure\\Repositories',
                        'services' => 'App\\Infrastructure\\Services',
                        'persistence' => 'App\\Infrastructure\\Persistence',
                        'external' => 'App\\Infrastructure\\External',
                    ],
                ],
                'ui' => [
                    'path' => 'app/UI',
                    'namespaces' => [
                        'controllers' => 'App\\UI\\Controllers',
                        'requests' => 'App\\UI\\Requests',
                        'resources' => 'App\\UI\\Resources',
                        'middleware' => 'App\\UI\\Middleware',
                    ],
                ],
            ],
        ],
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
        'cqrs' => [
            'enabled' => true,
            'commands' => [
                'path' => 'app/Commands',
                'namespace' => 'App\\Commands',
                'suffix' => 'Command',
            ],
            'queries' => [
                'path' => 'app/Queries',
                'namespace' => 'App\\Queries',
                'suffix' => 'Query',
            ],
            'handlers' => [
                'path' => 'app/Handlers',
                'namespace' => 'App\\Handlers',
                'command_suffix' => 'CommandHandler',
                'query_suffix' => 'QueryHandler',
            ],
        ],
        'event_bus' => [
            'enabled' => true,
            'events' => [
                'path' => 'app/Events',
                'namespace' => 'App\\Events',
                'suffix' => 'Event',
            ],
            'listeners' => [
                'path' => 'app/Listeners',
                'namespace' => 'App\\Listeners',
                'suffix' => 'Listener',
            ],
        ],
        'modular' => [
            'enabled' => true,
            'base_path' => 'app/Modules',
            'base_namespace' => 'App\\Modules',
            'structure' => [
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
            ],
            'default_options' => [
                'with_tests' => true,
                'with_migrations' => true,
                'with_seeders' => true,
                'with_routes' => true,
                'with_config' => true,
                'with_views' => false,
                'with_assets' => false,
            ],
        ],
        'hexagonal' => [
            'enabled' => true,
            'base_path' => 'app/Hexagonal',
            'base_namespace' => 'App\\Hexagonal',
            'structure' => [
                'Domain/Entities',
                'Domain/Ports',
                'Application/Services',
                'Infrastructure/Adapters',
                'Infrastructure/database/migrations',
                'UI/Adapters',
                'UI/routes',
                'Tests',
            ],
            'default_options' => [
                'with_tests' => true,
                'with_migrations' => true,
                'with_routes' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Naming Conventions
    |--------------------------------------------------------------------------
    |
    | Configure naming conventions for generated files
    |
    */
    'naming' => [
        'case' => 'pascal', // pascal, camel, snake, kebab
        'separator' => '_',
        'pluralize' => true,
        'suffixes' => [
            'repository' => 'Repository',
            'repository_interface' => 'RepositoryInterface',
            'service' => 'Service',
            'command' => 'Command',
            'query' => 'Query',
            'handler' => 'Handler',
            'event' => 'Event',
            'listener' => 'Listener',
            'controller' => 'Controller',
            'request' => 'Request',
            'resource' => 'Resource',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Engine
    |--------------------------------------------------------------------------
    |
    | Configuration for template engine and stub files
    |
    */
    'templates' => [
        'stub_path' => base_path('stubs/architex'),
        'default_stub_path' => __DIR__ . '/../stubs',
        'variables' => [
            'app_namespace' => 'App',
            'author' => 'Laravel Architex',
            'year' => date('Y'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Registration
    |--------------------------------------------------------------------------
    |
    | Automatically register generated classes in service providers
    |
    */
    'auto_register' => [
        'enabled' => true,
        'providers' => [
            'App\\Providers\\RepositoryServiceProvider',
            'App\\Providers\\ServiceServiceProvider',
            'App\\Providers\\EventServiceProvider',
        ],
    ],
]; 