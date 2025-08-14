# Laravel Architex Test Guide

This guide shows you how to test the Laravel Architex package in a real Laravel application.

## 🚀 Quick Start

### 1. Installation
```bash
# Package is already installed in this test app
composer require laravel-architex/architecture-generator:dev-main
```

### 2. Publish Configuration
```bash
php artisan vendor:publish --tag=architex-config
```

### 3. Test Commands
```bash
# Test all features
php artisan architex:test

# Test individual commands
php artisan make:repository User
php artisan make:service User
php artisan make:ddd UserManagement
php artisan make:cqrs CreateUser
php artisan make:event UserCreated
```

## 📁 Generated Files

### Repository Pattern
- `app/Repositories/Interfaces/UserRepositoryInterface.php`
- `app/Repositories/UserRepository.php`

### Service Layer
- `app/Services/UserService.php`

### DDD Structure
```
app/Domain/UserManagement/
├── Entities/
├── Repositories/
├── Services/
├── Events/
└── Exceptions/

app/Application/UserManagement/
├── Services/
├── Commands/
├── Queries/
└── Handlers/

app/Infrastructure/UserManagement/
├── Repositories/
├── Services/
├── Persistence/
└── External/

app/UI/UserManagement/
├── Controllers/
├── Requests/
├── Resources/
└── Middleware/
```

### CQRS Pattern
- `app/Commands/CreateUserCommand.php`
- `app/Queries/CreateUserQuery.php`
- `app/Handlers/CreateUserHandler.php`

### Event Bus
- `app/Events/UserCreatedEvent.php`
- `app/Listeners/UserCreatedListener.php`

## 🧪 Testing Features

### 1. Service Layer Pattern
```php
// In UserController
public function index()
{
    $users = $this->userService->getAll();
    return response()->json(['data' => $users]);
}
```

### 2. Repository Pattern
```php
// In UserController
public function show(int $id)
{
    $user = $this->userRepository->find($id);
    return response()->json(['data' => $user]);
}
```

### 3. CQRS Pattern
```php
// In UserController
public function testCqrs(Request $request)
{
    $command = new CreateUserCommand(
        $request->name,
        $request->description,
        $request->all()
    );
    return response()->json(['command' => $command->toArray()]);
}
```

## 🌐 API Testing

### Start Development Server
```bash
php artisan serve
```

### Test API Endpoints

1. **Health Check**
```bash
curl http://localhost:8000/api/health
```

2. **Get Users (Service Layer)**
```bash
curl http://localhost:8000/api/architex-test/users
```

3. **Create User (Service Layer)**
```bash
curl -X POST http://localhost:8000/api/architex-test/users \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'
```

4. **Get User by ID (Repository Pattern)**
```bash
curl http://localhost:8000/api/architex-test/users/1
```

5. **Update User (Repository Pattern)**
```bash
curl -X PUT http://localhost:8000/api/architex-test/users/1 \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane Doe","email":"jane@example.com"}'
```

6. **Search Users (Service Layer)**
```bash
curl "http://localhost:8000/api/architex-test/users/search?q=john"
```

7. **Test CQRS Command**
```bash
curl -X POST http://localhost:8000/api/architex-test/cqrs-test \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","description":"Test Description"}'
```

## 🔧 Configuration

### Customize Templates
Edit files in `stubs/architex/` directory to customize generated code.

### Configuration File
Edit `config/architex.php` to customize:
- File paths and namespaces
- Naming conventions
- Architecture patterns
- Auto registration

## 📊 Test Results

### ✅ Working Features
- ✅ Repository Pattern Generation
- ✅ Service Layer Generation
- ✅ DDD Structure Generation
- ✅ CQRS Pattern Generation
- ✅ Event Bus Generation
- ✅ Artisan Commands Registration
- ✅ File Generation
- ✅ Template Engine
- ✅ Configuration System

### 🔧 Architecture Patterns Tested
1. **Repository Pattern**: Interface + Implementation with CRUD methods
2. **Service Layer**: Business logic services with common operations
3. **DDD**: Complete 4-layer structure (Domain, Application, Infrastructure, UI)
4. **CQRS**: Commands, Queries, and Handlers
5. **Event Bus**: Events and Listeners

### 🎯 Benefits Demonstrated
- ✅ Rapid project structure setup
- ✅ Consistent coding conventions
- ✅ Standardized architecture patterns
- ✅ Template customization
- ✅ Auto registration in service providers
- ✅ Laravel integration

## 🚀 Next Steps

1. **Customize Templates**: Edit stub files in `stubs/architex/`
2. **Add More Patterns**: Extend the package with new architecture patterns
3. **Integration Testing**: Add more comprehensive tests
4. **Documentation**: Create detailed documentation for your team
5. **Production Use**: Use in real Laravel projects

## 📝 Notes

- This test app uses PHP 7.4 and Laravel 8.x
- All generated files are compatible with PHP 7.4+
- The package supports multiple Laravel versions (8.x, 9.x, 10.x, 11.x)
- Database connection is required for full functionality testing
- Custom templates can be published and modified

---

**Laravel Architex** - Successfully tested and ready for production use! 🎉 