# Laravel Architex - Folder Structure Guide

Complete guide to folder structures generated when running Laravel Architex commands.

## 📁 Repository Pattern Structure

### Command: `php artisan make:repository User --service`

```
app/
├── Repositories/
│   ├── Base/
│   │   └── BaseRepository.php                    # Trait with common methods
│   ├── Interfaces/
│   │   └── UserRepositoryInterface.php          # Interface for User repository
│   ├── UserRepository.php                       # Implementation of User repository
│   └── RepositoryServiceProvider.php            # Service provider for repositories
├── Services/
│   ├── Interfaces/
│   │   └── UserServiceInterface.php             # Interface for User service
│   ├── Implementations/
│   │   └── UserService.php                      # Implementation of User service
│   └── ServiceServiceProvider.php               # Service provider for services
├── Http/
│   ├── Controllers/
│   │   └── UserController.php                   # Controller with dependency injection
│   └── Requests/
│       ├── StoreUserRequest.php                 # Form request for create
│       └── UpdateUserRequest.php                # Form request for update
└── config/
    └── app.php                                  # Auto-added service providers
```

### Generated Files:

#### 1. BaseRepository.php (Trait)
```php
<?php
namespace App\Repositories\Base;

trait BaseRepository
{
    // CRUD methods: getAll, findById, create, update, delete
    // Query methods: findBy, findByCriteria, count, exists
    // Pagination: paginate
}
```

#### 2. UserRepositoryInterface.php
```php
<?php
namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    // All methods from BaseRepository + custom methods
    public function findByEmail(string $email): ?object;
}
```

#### 3. UserRepository.php
```php
<?php
namespace App\Repositories;

class UserRepository implements UserRepositoryInterface
{
    use BaseRepository;
    
    public function findByEmail(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }
}
```

#### 4. RepositoryServiceProvider.php
```php
<?php
namespace App\Repositories;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
```

#### 5. UserServiceInterface.php
```php
<?php
namespace App\Services\Interfaces;

interface UserServiceInterface
{
    public function getAllUsers(): array;
    public function findUserById(int $id): ?object;
    public function createUser(array $data): object;
    public function updateUser(int $id, array $data): ?object;
    public function deleteUser(int $id): bool;
}
```

#### 6. UserService.php
```php
<?php
namespace App\Services\Implementations;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}
    
    // Implementation of all methods
}
```

#### 7. ServiceServiceProvider.php
```php
<?php
namespace App\Services;

class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }
}
```

#### 8. UserController.php
```php
<?php
namespace App\Http\Controllers;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {}
    
    // CRUD methods with dependency injection
}
```

#### 9. StoreUserRequest.php & UpdateUserRequest.php
```php
<?php
namespace App\Http\Requests;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ];
    }
}
```

## 📁 Service Layer Structure

### Command: `php artisan make:service User`

```
app/
├── Services/
│   ├── Interfaces/
│   │   └── UserServiceInterface.php             # Interface for User service
│   ├── Implementations/
│   │   └── UserService.php                      # Implementation of User service
│   └── ServiceServiceProvider.php               # Service provider for services
```

## 📁 DDD Structure

### Command: `php artisan make:ddd UserManagement`

```
app/
├── Domain/
│   └── UserManagement/
│       ├── Entities/
│       │   └── UserManagementEntities.php       # Domain entities
│       ├── Events/
│       │   ├── UserManagementCreated.php        # Domain events
│       │   ├── UserManagementUpdated.php
│       │   └── UserManagementDeleted.php
│       ├── Exceptions/
│       │   └── UserManagementException.php      # Domain exceptions
│       ├── Repositories/
│       │   └── UserManagementRepositoryInterface.php
│       ├── Services/
│       │   └── UserManagementDomainService.php  # Domain services
│       └── ValueObjects/
│           └── UserManagementId.php             # Value objects
├── Application/
│   └── UserManagement/
│       ├── Commands/
│       │   └── CreateUserManagementCommand.php  # Application commands
│       ├── DTOs/
│       │   └── UserManagementDTO.php            # Data transfer objects
│       ├── Exceptions/
│       │   └── UserManagementApplicationException.php
│       ├── Handlers/
│       │   └── CreateUserManagementHandler.php  # Command handlers
│       ├── Queries/
│       │   └── GetUserManagementQuery.php       # Application queries
│       └── Services/
│           └── UserManagementApplicationService.php
├── Infrastructure/
│   └── UserManagement/
│       ├── Database/
│       │   ├── Factories/
│       │   │   └── UserManagementFactory.php    # Model factories
│       │   ├── Migrations/
│       │   │   └── create_user_management_table.php
│       │   └── Seeders/
│       │       └── UserManagementSeeder.php     # Database seeders
│       ├── Exceptions/
│       │   └── UserManagementInfrastructureException.php
│       ├── Models/
│       │   └── UserManagement.php               # Eloquent models
│       ├── Providers/
│       │   └── UserManagementServiceProvider.php
│       └── Repositories/
│           └── EloquentUserManagementRepository.php
├── UI/
│   └── UserManagement/
│       ├── Controllers/
│       │   └── UserManagementController.php     # UI controllers
│       ├── Middleware/
│       │   └── UserManagementMiddleware.php     # Custom middleware
│       ├── Requests/
│       │   ├── StoreUserManagementRequest.php   # Form requests
│       │   └── UpdateUserManagementRequest.php
│       ├── Resources/
│       │   ├── UserManagementCollection.php     # API resources
│       │   └── UserManagementResource.php
│       └── Routes/
│           └── user-management-routes.php        # Route definitions
└── Tests/
    ├── Application/
    │   └── UserManagementApplicationServiceTest.php
    ├── Domain/
    │   └── UserManagementEntityTest.php
    ├── Infrastructure/
    │   └── UserManagementRepositoryTest.php
    └── UI/
        └── UserManagementControllerTest.php
```

## 📁 CQRS Structure

### Command: `php artisan make:cqrs CreateUser`

```
app/
├── Commands/
│   └── CreateUserCommand.php                    # Command class
├── Queries/
│   └── CreateUserQuery.php                      # Query class
├── Handlers/
│   ├── CreateUserCommandHandler.php             # Command handler
│   └── CreateUserQueryHandler.php               # Query handler
├── Requests/
│   └── CreateUserRequest.php                    # Form request
└── Resources/
    └── CreateUserResource.php                   # API resource
```

## 📁 Event Bus Structure

### Command: `php artisan make:event UserCreated`

```
app/
├── Events/
│   └── UserCreatedEvent.php                     # Event class
├── Listeners/
│   └── UserCreatedListener.php                  # Listener class
├── Requests/
│   └── UserCreatedRequest.php                   # Form request
└── Resources/
    └── UserCreatedResource.php                  # API resource
```

## 📁 Modular Architecture Structure

### Command: `php artisan architex:modular UserManagement`

```
app/
├── Modules/
│   └── UserManagement/
│       ├── Assets/                              # Module assets
│       ├── Config/
│       │   └── user-management.php              # Module config
│       ├── Controllers/
│       │   └── UserManagementController.php     # Module controllers
│       ├── Database/
│       │   ├── Migrations/
│       │   │   └── create_user_management_table.php
│       │   └── Seeders/
│       │       └── UserManagementSeeder.php
│       ├── Models/
│       │   └── UserManagement.php               # Module models
│       ├── Providers/
│       │   └── UserManagementServiceProvider.php
│       ├── README.md                            # Module documentation
│       ├── Repositories/
│       │   └── UserManagementRepository.php     # Module repositories
│       ├── Routes/
│       │   └── user-management-routes.php       # Module routes
│       ├── Services/
│       │   └── UserManagementService.php        # Module services
│       ├── Tests/
│       │   └── UserManagementTest.php           # Module tests
│       └── Views/                               # Module views
```

## 📁 Hexagonal Architecture Structure

### Command: `php artisan make:hexagonal UserManagement`

```
app/
├── Hexagonal/
│   └── UserManagement/
│       ├── Application/
│       │   └── UserManagementApplicationService.php
│       ├── Domain/
│       │   ├── Entities/
│       │   │   └── UserManagementEntity.php     # Domain entities
│       │   └── Ports/
│       │       ├── UserManagementRepositoryPort.php
│       │       └── UserManagementServicePort.php
│       ├── Infrastructure/
│       │   ├── Adapters/
│       │   │   └── UserManagementRepositoryAdapter.php
│       │   └── Database/
│       │       └── migrations/
│       │           └── create_user_management_table.php
│       ├── Shared/
│       │   ├── UserManagementServiceProvider.php
│       │   └── UserManagementTest.php
│       └── UI/
│           ├── Adapters/
│           │   └── UserManagementControllerAdapter.php
│           ├── Requests/
│           │   └── UserManagementRequest.php
│           ├── Resources/
│           │   └── UserManagementResource.php
│           └── Routes/
│               └── user-management-routes.php
```

## 🔧 Configuration Files

### config/architex.php
```php
<?php
return [
    'patterns' => [
        'repository' => [
            'enabled' => true,
            'path' => 'app/Repositories',
            'namespace' => 'App\\Repositories',
            'base_namespace' => 'App\\Repositories\\Base',
        ],
        'service' => [
            'enabled' => true,
            'path' => 'app/Services',
            'namespace' => 'App\\Services',
        ],
        // ... other patterns
    ],
    'naming' => [
        'case' => 'pascal',
        'suffixes' => [
            'repository' => 'Repository',
            'repository_interface' => 'RepositoryInterface',
            'service' => 'Service',
            'service_interface' => 'ServiceInterface',
        ],
    ],
];
```

### config/app.php (Auto-updated)
```php
'providers' => [
    // ... Laravel Framework Service Providers
    
    // ... Package Service Providers
    
    // ... Application Service Providers
    App\Repositories\RepositoryServiceProvider::class,  // Auto-added
    App\Services\ServiceServiceProvider::class,         // Auto-added
    App\Providers\AppServiceProvider::class,
    // ...
],
```

## 📋 Summary

| Command | Main Folders | Key Files | Auto Registration |
|---------|-------------|-----------|-------------------|
| `make:repository User --service` | `app/Repositories/`, `app/Services/` | BaseRepository.php, UserRepository.php, UserService.php | ✅ Service Providers |
| `make:service User` | `app/Services/` | UserService.php, ServiceServiceProvider.php | ✅ Service Provider |
| `make:ddd UserManagement` | `app/Domain/`, `app/Application/`, `app/Infrastructure/`, `app/UI/` | Complete DDD structure | ❌ Manual |
| `make:cqrs CreateUser` | `app/Commands/`, `app/Queries/`, `app/Handlers/` | Command.php, Query.php, Handler.php | ❌ Manual |
| `make:event UserCreated` | `app/Events/`, `app/Listeners/` | Event.php, Listener.php | ❌ Manual |
| `architex:modular UserManagement` | `app/Modules/UserManagement/` | Complete module structure | ❌ Manual |
| `make:hexagonal UserManagement` | `app/Hexagonal/UserManagement/` | Complete hexagonal structure | ❌ Manual |

## 🚀 Best Practices

1. **Repository Pattern**: Always use `--service` flag to include service layer
2. **DDD**: Use for complex domains
3. **CQRS**: Use for complex operations
4. **Modular**: Use for independent modules
5. **Hexagonal**: Use for clean architecture

## 📝 Notes

- All service providers are automatically registered in `config/app.php`
- BaseRepository is a trait, not an abstract class
- Each repository implements its own interface for type safety
- Service layer separates business logic from data access
- PHPDoc annotations are added for better IDE support
