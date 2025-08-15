# Laravel Architex - Architecture Generator

Laravel Architex is a powerful tool that helps Laravel developers quickly initialize project structure based on popular architecture patterns, automatically generating folders, classes, interfaces, service providers, and necessary files based on templates.

## 🚀 Key Features

### Supported Architectures:
- **DDD (Domain Driven Design)** - Create 4-layer structure: Domain, Application, Infrastructure, UI
- **Repository Pattern** - Create interface and implementation for repositories
- **Service Layer** - Create service classes with basic methods
- **CQRS (Command Query Responsibility Segregation)** - Create commands, queries and handlers
- **Event Bus** - Create events and listeners
- **Modular/Package-based Architecture** - Create complete module structure with controllers, models, services, repositories, routes, config, tests, and more
- **Hexagonal Architecture (Ports and Adapters)** - Create clean architecture with domain isolation, ports, and adapters

### Additional Features:
- ✅ Configurable naming conventions (class names, interfaces, namespaces, folder structure)
- ✅ Integrated Artisan commands for quick component generation
- ✅ Template engine (stub files) for customizing generated code
- ✅ Configuration through `architex.php` file in config/
- ✅ Auto registration in service providers

## 📦 Installation

### System Requirements:
- PHP >= 7.4
- Laravel >= 8.0

### Quick Setup (Recommended)

```bash
# Clone repository
git clone <repository-url>
cd Laravel-Architecture-Generator

# Run automated setup
chmod +x setup.sh
./setup.sh
```

### Manual Installation

```bash
# Install package
composer require laravel-architex/architecture-generator

# Publish configuration
php artisan vendor:publish --tag=architex-config
```

### For Development/Testing

```bash
# Install package dependencies
composer install

# Run package tests
chmod +x run-tests.sh
./run-tests.sh all

# Create test Laravel app
composer create-project laravel/laravel test-laravel-app
cd test-laravel-app

# Install Laravel Architex
composer config repositories.laravel-architex path ../
composer require laravel-architex/architecture-generator:dev-main

# Publish configuration
php artisan vendor:publish --tag=architex-config

# Fix missing files (if needed)
chmod +x fix-missing-files.sh
./fix-missing-files.sh
```

### Setup RepositoryService

```bash
# Register RepositoryServiceProvider in config/app.php
# Add to providers array:
App\Providers\RepositoryServiceProvider::class,

# Or use the provided service provider from Laravel Architex
# It will be automatically registered when you publish the config
```

**Quick Start with RepositoryService:**
```php
// In your controller
class UserController extends Controller
{
    public function index()
    {
        // That's it! No need to create individual repositories
        $users = Repository::model(User::class)->paginate(15);
        return response()->json($users);
    }
}
```

## 🛠️ Usage

### 1. Repository Pattern

#### Option A: Traditional Repository Pattern

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
- `app/Repositories/UserRepository.php` (extends BaseRepository)

#### Option B: RepositoryService Pattern (Recommended)

```bash
# No need to create individual repositories!
# Just use RepositoryService for all models
```

**Usage:**
```php
// Clean and simple!
$users = Repository::model(User::class)->paginate(15);
$posts = Repository::model(Post::class)->findWhere(['status' => 'published']);
$orders = Repository::model(Order::class)->with('items')->all();
```

**Benefits:**
- ✅ No need to create individual repository classes
- ✅ Single service handles all models
- ✅ Clean Facade syntax
- ✅ Easy to test and mock
- ✅ Consistent API across all models

**Base Repository Features:**
- ✅ Full CRUD operations (create, read, update, delete)
- ✅ Advanced query methods (findWhere, findWhereIn, etc.)
- ✅ Pagination support
- ✅ Criteria pattern for complex queries
- ✅ Presenter pattern support
- ✅ Validation integration
- ✅ Event dispatching
- ✅ Relationship handling
- ✅ Search functionality
- ✅ Ordering and limiting
- ✅ Scope queries

**RepositoryService Pattern (Recommended):**
- ✅ Single service for all models
- ✅ Clean Facade syntax
- ✅ Dependency injection ready
- ✅ Contract-based architecture
- ✅ Easy to test and mock
- ✅ Reusable across the application

**Usage Examples:**

### 1. Using RepositoryService (Recommended)

```php
// In your controller
class UserController extends Controller
{
    protected $repositoryService;

    public function __construct(RepositoryServiceInterface $repositoryService)
    {
        $this->repositoryService = $repositoryService;
    }

    public function index()
    {
        // Set model and get all users with pagination
        $users = $this->repositoryService->model(\App\Models\User::class)->paginate(15);
        
        // Find by criteria
        $activeUsers = $this->repositoryService->model(\App\Models\User::class)
            ->findWhere(['status' => 'active']);
        
        // Advanced search
        $searchResults = $this->repositoryService->model(\App\Models\User::class)
            ->findWhere([
                'name' => ['LIKE', '%john%'],
                'created_at' => ['DATE', '>=', '2023-01-01']
            ]);
        
        // With relationships
        $usersWithPosts = $this->repositoryService->model(\App\Models\User::class)
            ->with('posts')->all();
        
        return response()->json($users);
    }
}
```

### 2. Using Repository Facade

```php
// In your controller
class UserController extends Controller
{
    public function index()
    {
        // Using Facade - much cleaner!
        $users = Repository::model(\App\Models\User::class)->paginate(15);
        
        $activeUsers = Repository::model(\App\Models\User::class)
            ->findWhere(['status' => 'active']);
        
        $searchResults = Repository::model(\App\Models\User::class)
            ->findWhere([
                'name' => ['LIKE', '%john%'],
                'created_at' => ['DATE', '>=', '2023-01-01']
            ]);
        
        $usersWithPosts = Repository::model(\App\Models\User::class)
            ->with('posts')->all();
        
        return response()->json($users);
    }
}
```

### 3. Using Dependency Injection

```php
// In your controller
class UserController extends Controller
{
    public function index(RepositoryServiceInterface $repository)
    {
        $users = $repository->model(\App\Models\User::class)->paginate(15);
        return response()->json($users);
    }
}
```

### 4. Complete Usage Examples

```php
// CRUD Operations
$user = Repository::model(User::class)->create(['name' => 'John', 'email' => 'john@example.com']);
$user = Repository::model(User::class)->find(1);
$user = Repository::model(User::class)->update(['name' => 'Jane'], 1);
Repository::model(User::class)->delete(1);

// Find Operations
$user = Repository::model(User::class)->findByField('email', 'john@example.com');
$users = Repository::model(User::class)->findWhere(['status' => 'active']);
$users = Repository::model(User::class)->findWhereIn('id', [1, 2, 3]);
$users = Repository::model(User::class)->findWhereNotIn('id', [1, 2, 3]);
$users = Repository::model(User::class)->findWhereBetween('created_at', ['2023-01-01', '2023-12-31']);

// Pagination
$users = Repository::model(User::class)->paginate(15);
$users = Repository::model(User::class)->simplePaginate(15);

// Relationships
$users = Repository::model(User::class)->with('posts')->all();
$users = Repository::model(User::class)->withCount('posts')->all();
$users = Repository::model(User::class)->whereHas('posts', function($query) {
    $query->where('status', 'published');
})->all();

// Ordering & Limiting
$users = Repository::model(User::class)->orderBy('created_at', 'desc')->all();
$users = Repository::model(User::class)->take(10)->all();
$users = Repository::model(User::class)->limit(10)->all();

// Advanced Search
$users = Repository::model(User::class)->findWhere([
    'name' => ['LIKE', '%john%'],
    'created_at' => ['DATE', '>=', '2023-01-01'],
    'status' => ['IN', ['active', 'pending']]
]);

// Count
$count = Repository::model(User::class)->count(['status' => 'active']);

// First or Create
$user = Repository::model(User::class)->firstOrCreate(['email' => 'john@example.com']);
$user = Repository::model(User::class)->firstOrNew(['email' => 'john@example.com']);

// Update or Create
$user = Repository::model(User::class)->updateOrCreate(
    ['email' => 'john@example.com'],
    ['name' => 'John Doe']
);

// Sync Relations
Repository::model(User::class)->sync(1, 'roles', [1, 2, 3]);
Repository::model(User::class)->syncWithoutDetaching(1, 'roles', [1, 2, 3]);
```

### 5. Service Provider Registration

```php
// config/app.php
'providers' => [
    // ...
    App\Providers\RepositoryServiceProvider::class,
],

// app/Providers/RepositoryServiceProvider.php
public function register()
{
    $this->app->singleton('repository', function ($app) {
        return new RepositoryService();
    });
    
    $this->app->bind(RepositoryServiceInterface::class, RepositoryService::class);
}
```

### 6. Testing with RepositoryService

```php
// tests/Feature/UserTest.php
class UserTest extends TestCase
{
    public function test_can_get_users_with_repository()
    {
        // Mock the repository service
        $mockRepository = Mockery::mock(RepositoryServiceInterface::class);
        $mockRepository->shouldReceive('model')
            ->with(\App\Models\User::class)
            ->andReturnSelf();
        $mockRepository->shouldReceive('paginate')
            ->with(15)
            ->andReturn(collect([]));
        
        $this->app->instance(RepositoryServiceInterface::class, $mockRepository);
        
        $response = $this->get('/api/users');
        $response->assertStatus(200);
    }
}
```

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

### 6. Modular/Package-based Architecture

```bash
# Create complete modular structure
php artisan architex:modular UserManagement

# Create with specific options
php artisan architex:modular UserManagement --with-tests --with-migrations --with-seeders --with-routes --with-config

# Create with custom path and namespace
php artisan architex:modular UserManagement --path=app/Modules --namespace=App\\Modules

# Create with all features
php artisan architex:modular UserManagement --with-tests --with-migrations --with-seeders --with-routes --with-config --with-views --with-assets

# Overwrite existing files
php artisan architex:modular UserManagement --force
```

**Result:**
```
app/Modules/UserManagement/
├── Controllers/
│   └── UserManagementController.php
├── Models/
│   └── UserManagement.php
├── Services/
│   └── UserManagementService.php
├── Repositories/
│   └── UserManagementRepository.php
├── Providers/
│   └── UserManagementServiceProvider.php
├── Routes/
│   └── web.php
├── Config/
│   └── usermanagement.php
├── Views/ (optional)
├── Assets/ (optional)
├── Database/
│   ├── Migrations/
│   │   └── 2024_01_01_000000_create_user_managements_table.php
│   └── Seeders/
│       └── UserManagementSeeder.php
├── Tests/
│   └── UserManagementTest.php
└── README.md
```

**Features:**
- ✅ Complete CRUD operations with controllers
- ✅ Repository pattern implementation
- ✅ Service layer with business logic
- ✅ Database migrations and seeders
- ✅ Comprehensive test coverage
- ✅ Module configuration management
- ✅ Route management with middleware
- ✅ Service provider for module registration
- ✅ Optional view templates and assets
- ✅ Documentation and usage examples

### 7. Hexagonal Architecture (Ports and Adapters)

```bash
# Create complete hexagonal structure
php artisan architex:hexagonal User

# Create with specific options
php artisan architex:hexagonal User --with-tests --with-migrations --with-routes

# Create with custom path and namespace
php artisan architex:hexagonal User --path=app/Hexagonal --namespace=App\\Hexagonal

# Create with all features
php artisan architex:hexagonal User --with-tests --with-migrations --with-routes --with-config

# Overwrite existing files
php artisan architex:hexagonal User --force
```

**Result:**
```
app/Hexagonal/User/
├── Domain/
│   ├── Entities/
│   │   └── User.php
│   └── Ports/
│       ├── UserRepositoryPort.php
│       └── UserServicePort.php
├── Application/
│   └── Services/
│       └── UserApplicationService.php
├── Infrastructure/
│   ├── Adapters/
│   │   └── UserRepositoryAdapter.php
│   └── database/migrations/
│       └── create_users_table.php
├── UI/
│   ├── Adapters/
│   │   └── UserControllerAdapter.php
│   └── routes/
│       └── user_routes.php
├── Tests/
│   └── UserHexagonalTest.php
└── UserServiceProvider.php
```

**Features:**
- ✅ Domain entities with business logic
- ✅ Port interfaces for dependency inversion
- ✅ Application services for use cases
- ✅ Infrastructure adapters for external concerns
- ✅ UI adapters for primary ports
- ✅ Service provider for dependency injection
- ✅ Database migrations and tests
- ✅ Route management
- ✅ Clean separation of concerns

## ⚙️ Configuration

### Config File: `config/architex.php`

The configuration file allows you to customize:
- **Architecture patterns** (DDD, Repository, Service, CQRS, Event Bus, Modular)
- **Naming conventions** (class names, interfaces, namespaces)
- **Template engine** settings
- **Auto registration** options

```bash
# Publish config file
php artisan vendor:publish --tag=architex-config
```

**Key Configuration Sections:**
- `patterns` - Enable/disable and configure architecture patterns
- `naming` - Customize naming conventions and suffixes
- `templates` - Configure template engine and stub paths
- `auto_register` - Automatic service provider registration

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

## 📚 Documentation

- **[README.md](README.md)** - Main documentation and usage guide
- **[DEVELOPMENT.md](DEVELOPMENT.md)** - Development setup, testing, and contributing guide
- **[TESTING.md](TESTING.md)** - Detailed testing information
- **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)** - Step-by-step installation guide

## 🆘 Support

If you encounter issues or have questions, please:

- Create an issue on GitHub
- Contact us via email: team@laravel-architex.com
- Join our Discord community

---

**Laravel Architex** - Help you build Laravel architecture quickly and professionally! 🚀 