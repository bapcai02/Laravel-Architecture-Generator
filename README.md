# Laravel Architex - Architecture Generator

Laravel Architex is a powerful tool that helps Laravel developers quickly initialize project structure based on popular architecture patterns, automatically generating folders, classes, interfaces, service providers, and necessary files based on templates.

## 🚀 Key Features

### Supported Architectures:
- **DDD (Domain Driven Design)** - Create 4-layer structure: Domain, Application, Infrastructure, UI
- **Repository Pattern** - Create interface and implementation for repositories
- **Service Layer** - Create service classes with basic methods
- **CQRS (Command Query Responsibility Segregation)** - Create commands, queries and handlers
- **Event Bus** - Create events and listeners

### Additional Features:
- ✅ Configurable naming conventions (class names, interfaces, namespaces, folder structure)
- ✅ Integrated Artisan commands for quick component generation
- ✅ Template engine (stub files) for customizing generated code
- ✅ Configuration through `architex.php` file in config/
- ✅ Auto registration in service providers

## 📦 Installation

### System Requirements:
- PHP >= 8.1
- Laravel >= 10.0

### Install Package:

```bash
composer require laravel-architex/architecture-generator
```

### Publish Config:

```bash
php artisan vendor:publish --tag=architex-config
```

## 🛠️ Usage

### 1. Repository Pattern

```bash
# Create repository for User model
php artisan make:repository User

# Create repository with custom model
php artisan make:repository User --model=App\Models\User

# Overwrite existing files
php artisan make:repository User --force
```

**Result:**
- `app/Repositories/Interfaces/UserRepositoryInterface.php`
- `app/Repositories/UserRepository.php`

### 2. Service Layer

```bash
# Create service for User
php artisan make:service User

# Overwrite existing files
php artisan make:service User --force
```

**Result:**
- `app/Services/UserService.php`

### 3. DDD (Domain Driven Design)

```bash
# Create complete DDD module
php artisan make:ddd UserManagement

# Create only specific layers
php artisan make:ddd UserManagement --layers=domain,application

# Overwrite existing files
php artisan make:ddd UserManagement --force
```

**Result:**
```
app/
├── Domain/
│   └── UserManagement/
│       ├── Entities/
│       ├── Repositories/
│       ├── Services/
│       ├── Events/
│       └── Exceptions/
├── Application/
│   └── UserManagement/
│       ├── Services/
│       ├── Commands/
│       ├── Queries/
│       └── Handlers/
├── Infrastructure/
│   └── UserManagement/
│       ├── Repositories/
│       ├── Services/
│       ├── Persistence/
│       └── External/
└── UI/
    └── UserManagement/
        ├── Controllers/
        ├── Requests/
        ├── Resources/
        └── Middleware/
```

### 4. CQRS (Command Query Responsibility Segregation)

```bash
# Create complete CQRS structure
php artisan make:cqrs CreateUser

# Create only command
php artisan make:command CreateUser

# Create only query
php artisan make:query GetUser

# Overwrite existing files
php artisan make:cqrs CreateUser --force
```

**Result:**
- `app/Commands/CreateUserCommand.php`
- `app/Queries/GetUserQuery.php`
- `app/Handlers/CreateUserCommandHandler.php`
- `app/Handlers/GetUserQueryHandler.php`

### 5. Event Bus

```bash
# Create event and listener
php artisan make:event UserCreated

# Overwrite existing files
php artisan make:event UserCreated --force
```

**Result:**
- `app/Events/UserCreatedEvent.php`
- `app/Listeners/UserCreatedListener.php`

## ⚙️ Configuration

### Config File: `config/architex.php`

```php
return [
    'patterns' => [
        'ddd' => [
            'enabled' => true,
            'layers' => [
                'domain' => [
                    'path' => 'app/Domain',
                    'namespaces' => [
                        'entities' => 'App\\Domain\\Entities',
                        'repositories' => 'App\\Domain\\Repositories',
                        // ...
                    ],
                ],
                // ...
            ],
        ],
        'repository' => [
            'enabled' => true,
            'path' => 'app/Repositories',
            'namespace' => 'App\\Repositories',
            'interface_suffix' => 'RepositoryInterface',
            'implementation_suffix' => 'Repository',
        ],
        // ...
    ],

    'naming' => [
        'case' => 'pascal', // pascal, camel, snake, kebab
        'separator' => '_',
        'pluralize' => true,
        'suffixes' => [
            'repository' => 'Repository',
            'repository_interface' => 'RepositoryInterface',
            'service' => 'Service',
            // ...
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
];
```

## 🎨 Customizing Templates

### Create custom stub files:

1. Publish config files:
```bash
php artisan vendor:publish --tag=architex-config
```

2. Customize stub files in `stubs/architex/`

3. Available variables in templates:
- `{{namespace}}` - Class namespace
- `{{class_name}}` - Class name
- `{{interface_name}}` - Interface name
- `{{model_name}}` - Model name
- `{{model_namespace}}` - Model namespace
- `{{layer}}` - Layer name (for DDD)
- `{{sub_directory}}` - Subdirectory name (for DDD)

## 🔧 Auto Registration

The package supports automatic registration of generated classes in service providers:

```php
// config/architex.php
'auto_register' => [
    'enabled' => true,
    'providers' => [
        'App\\Providers\\RepositoryServiceProvider',
        'App\\Providers\\ServiceServiceProvider',
        'App\\Providers\\EventServiceProvider',
    ],
],
```

## 📚 Usage Examples

### Repository Pattern with Dependency Injection:

```php
// app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function index()
    {
        $users = $this->userRepository->all();
        return view('users.index', compact('users'));
    }
}
```

### Service Layer:

```php
// app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function store(Request $request)
    {
        $user = $this->userService->create($request->validated());
        return redirect()->route('users.show', $user);
    }
}
```

### CQRS:

```php
// app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $command = new CreateUserCommand(
            $request->name,
            $request->email,
            $request->validated()
        );
        
        $result = $this->commandBus->dispatch($command);
        
        return redirect()->route('users.show', $result);
    }
}
```

## 🤝 Contributing

We welcome all contributions! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Create a Pull Request

## 📄 License

This package is released under the MIT license. See the [LICENSE](LICENSE) file for more details.

## 🧪 Testing

### Quick Start

```bash
# Install dependencies
composer install

# Run all tests
./run-tests.sh all

# Run with coverage
./run-tests.sh coverage

# Run specific tests
./run-tests.sh specific tests/ArchitectureGeneratorTest.php
```

### Test Commands

```bash
# Run all tests
./vendor/bin/phpunit

# Run unit tests only
./run-tests.sh unit

# Run integration tests only
./run-tests.sh integration

# Run tests in watch mode
./run-tests.sh watch

# Run with verbose output
./vendor/bin/phpunit --verbose

# Run specific test method
./vendor/bin/phpunit --filter test_can_generate_repository
```

### Test Coverage

The test suite covers:

- ✅ **Architecture Generation**: All pattern generation methods
- ✅ **Template Engine**: Stub rendering and variable replacement
- ✅ **Artisan Commands**: Command execution and output
- ✅ **File Generation**: Directory and file creation
- ✅ **Configuration**: Config loading and merging

### 🚀 Real Laravel Application Testing

#### 1. Create Test Laravel App

```bash
# Create new Laravel application
composer create-project laravel/laravel test-laravel-app

# Navigate to test app
cd test-laravel-app

# Install Laravel Architex
composer require laravel-architex/architecture-generator:dev-main

# Publish configuration
php artisan vendor:publish --tag=architex-config
```

#### 2. Test All Commands

```bash
# Test Repository Pattern
php artisan make:repository User
# Output: Creating repository for: User
#         Repository created successfully!
#         Created files:
#           - app/Repositories/Interfaces/UserRepositoryInterface.php
#           - app/Repositories/UserRepository.php

# Test Service Layer
php artisan make:service User
# Output: Creating service: User
#         Service created successfully!
#           - app/Services/UserService.php

# Test DDD Structure
php artisan make:ddd UserManagement
# Output: Creating DDD module: UserManagement
#         DDD module created successfully!
#         Created files:
#           - app/Domain/UserManagement/Entities/UserManagementEntities.php
#           - app/Domain/UserManagement/Repositories/UserManagementRepositories.php
#           - app/Domain/UserManagement/Services/UserManagementServices.php
#           - app/Domain/UserManagement/Events/UserManagementEvents.php
#           - app/Domain/UserManagement/Exceptions/UserManagementExceptions.php
#           - app/Application/UserManagement/Services/UserManagementServices.php
#           - app/Application/UserManagement/Commands/UserManagementCommands.php
#           - app/Application/UserManagement/Queries/UserManagementQueries.php
#           - app/Application/UserManagement/Handlers/UserManagementHandlers.php
#           - app/Infrastructure/UserManagement/Repositories/UserManagementRepositories.php
#           - app/Infrastructure/UserManagement/Services/UserManagementServices.php
#           - app/Infrastructure/UserManagement/Persistence/UserManagementPersistence.php
#           - app/Infrastructure/UserManagement/External/UserManagementExternal.php
#           - app/UI/UserManagement/Controllers/UserManagementControllers.php
#           - app/UI/UserManagement/Requests/UserManagementRequests.php
#           - app/UI/UserManagement/Resources/UserManagementResources.php
#           - app/UI/UserManagement/Middleware/UserManagementMiddleware.php

# Test CQRS Pattern
php artisan make:cqrs CreateUser
# Output: Creating CQRS structure: CreateUser
#         CQRS structure created successfully!
#           - app/Commands/CreateUserCommand.php
#           - app/Queries/CreateUserQuery.php
#           - app/Handlers/CreateUserHandler.php

# Test Event Bus
php artisan make:event UserCreated
# Output: Event created successfully.
```

#### 3. Verify Generated Files

```bash
# Check Repository files
ls -la app/Repositories/
# Output: total 16
#         drwxr-xr-x  3 hadv hadv 4096 Thg 8  14 21:42 .
#         drwxrwxr-x 17 hadv hadv 4096 Thg 8  14 21:42 ..
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 Interfaces
#         -rw-rw-r--  1 hadv hadv 1678 Thg 8  14 21:42 UserRepository.php

# Check Service files
ls -la app/Services/
# Output: total 12
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 .
#         drwxrwxr-x 17 hadv hadv 4096 Thg 8  14 21:42 ..
#         -rw-rw-r--  1 hadv hadv 1273 Thg 8  14 21:42 UserService.php

# Check DDD structure
ls -la app/Domain/UserManagement/
# Output: total 28
#         drwxr-xr-x 7 hadv hadv 4096 Thg 8  14 21:42 .
#         drwxr-xr-x 3 hadv hadv 4096 Thg 8  14 21:42 ..
#         drwxr-xr-x 2 hadv hadv 4096 Thg 8  14 21:42 Entities
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 Events
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 Exceptions
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 Repositories
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 Services

# Check CQRS files
ls -la app/Commands/ app/Queries/ app/Handlers/
# Output: app/Commands/:
#         total 12
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 .
#         drwxrwxr-x 17 hadv hadv 4096 Thg 8  14 21:42 ..
#         -rw-rw-r--  1 hadv hadv  485 Thg 8  14 21:42 CreateUserCommand.php
#         app/Handlers/:
#         total 12
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 .
#         drwxrwxr-x 17 hadv hadv 4096 Thg 8  14 21:42 ..
#         -rw-rw-r--  1 hadv hadv  940 Thg 8  14 21:42 CreateUserHandler.php
#         app/Queries/:
#         total 12
#         drwxr-xr-x  2 hadv hadv 4096 Thg 8  14 21:42 .
#         drwxrwxr-x 17 hadv hadv 4096 Thg 8  14 21:42 ..
#         -rw-rw-r--  1 hadv hadv  477 Thg 8  14 21:42 CreateUserQuery.php
```

#### 4. Test API Integration

```bash
# Start development server
php artisan serve

# Test health check endpoint
curl http://localhost:8000/api/health
# Output: {
#   "status": "healthy",
#   "message": "Laravel Architex Test Application is running",
#   "timestamp": "2024-08-14T14:42:00.000000Z",
#   "architecture_patterns": {
#     "Repository Pattern": "Available",
#     "Service Layer": "Available",
#     "DDD": "Available",
#     "CQRS": "Available",
#     "Event Bus": "Available"
#   }
# }

# Test Repository Pattern API
curl http://localhost:8000/api/architex-test/users/1
# Output: {
#   "message": "User retrieved successfully",
#   "data": {...},
#   "architecture": "Repository Pattern"
# }

# Test Service Layer API
curl http://localhost:8000/api/architex-test/users
# Output: {
#   "message": "Users retrieved successfully",
#   "data": [...],
#   "architecture": "Service Layer Pattern"
# }

# Test CQRS Pattern API
curl -X POST http://localhost:8000/api/architex-test/cqrs-test \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","description":"Test Description"}'
# Output: {
#   "message": "CQRS Command created successfully",
#   "command_data": {
#     "name": "Test User",
#     "description": "Test Description",
#     "data": {...}
#   },
#   "architecture": "CQRS Pattern"
# }
```

#### 5. Test Summary

```bash
# Run comprehensive test
php artisan architex:test
# Output: 🚀 Laravel Architex Test Script
#         ================================
#         
#         1. Testing Service Layer Pattern:
#         ---------------------------------
#         ✅ UserService instantiated successfully
#         ✅ getAll() method works: 0 users found
#         ✅ Service Layer Pattern: PASSED
#         
#         2. Testing Repository Pattern:
#         ------------------------------
#         ✅ UserRepositoryInterface resolved successfully
#         ✅ all() method works: 0 users found
#         ✅ Repository Pattern: PASSED
#         
#         3. Testing CQRS Pattern:
#         -----------------------
#         ✅ CreateUserCommand created successfully
#         ✅ Command data: {"name":"Test User","description":"Test Description","data":{...}}
#         ✅ CQRS Pattern: PASSED
#         
#         4. Verifying Generated File Structure:
#         --------------------------------------
#         ✅ app/Repositories/Interfaces/UserRepositoryInterface.php exists
#         ✅ app/Repositories/UserRepository.php exists
#         ✅ app/Services/UserService.php exists
#         ✅ app/Commands/CreateUserCommand.php exists
#         ✅ app/Queries/CreateUserQuery.php exists
#         ✅ app/Handlers/CreateUserHandler.php exists
#         ✅ app/Domain/UserManagement/Entities/UserManagementEntities.php exists
#         ✅ app/Application/UserManagement/Services/UserManagementServices.php exists
#         ✅ app/Infrastructure/UserManagement/Repositories/UserManagementRepositories.php exists
#         ✅ app/UI/UserManagement/Controllers/UserManagementControllers.php exists
#         ✅ All generated files exist
#         
#         5. Testing Artisan Commands:
#         ---------------------------
#         ✅ make:repository command available
#         ✅ make:service command available
#         ✅ make:ddd command available
#         ✅ make:cqrs command available
#         ✅ make:event command available
#         ✅ All Artisan commands are registered
#         
#         📊 Test Summary:
#         ================
#         ✅ Service Layer Pattern: Working
#         ✅ Repository Pattern: Working
#         ✅ CQRS Pattern: Working
#         ✅ DDD Structure: Generated
#         ✅ Event Bus: Available
#         ✅ Artisan Commands: Registered
#         ✅ File Generation: Successful
#         
#         🎉 Laravel Architex is working perfectly!
```

### Test Results Summary

✅ **Package Installation**: SUCCESS  
✅ **Configuration Publishing**: SUCCESS  
✅ **Artisan Commands Registration**: SUCCESS  
✅ **Repository Pattern Generation**: SUCCESS  
✅ **Service Layer Generation**: SUCCESS  
✅ **DDD Structure Generation**: SUCCESS  
✅ **CQRS Pattern Generation**: SUCCESS  
✅ **Event Bus Generation**: SUCCESS  
✅ **File Structure Creation**: SUCCESS  
✅ **Template Engine**: SUCCESS  
✅ **Service Provider Integration**: SUCCESS  
✅ **API Integration**: SUCCESS  
✅ **Real Laravel App Testing**: SUCCESS
- ✅ **Artisan Commands**: All command functionality
- ✅ **Error Handling**: Exception scenarios
- ✅ **File System Operations**: Directory and file creation

For detailed testing information, see [TESTING.md](TESTING.md).

## 🆘 Support

If you encounter issues or have questions, please:

- Create an issue on GitHub
- Contact us via email: team@laravel-architex.com
- Join our Discord community

---

**Laravel Architex** - Help you build Laravel architecture quickly and professionally! 🚀 