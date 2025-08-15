# Development Guide

This document contains detailed information for developers who want to contribute to Laravel Architex or set up the development environment.

## 🛠️ Development Setup

### Prerequisites

- PHP >= 7.4
- Composer
- Laravel >= 8.0
- Git

### Local Development Setup

```bash
# Clone repository
git clone <repository-url>
cd Laravel-Architecture-Generator

# Install dependencies
composer install

# Run tests
./run-tests.sh all
```

### Testing Environment

```bash
# Create test Laravel app
composer create-project laravel/laravel test-laravel-app
cd test-laravel-app

# Install Laravel Architex for testing
composer config repositories.laravel-architex path ../
composer require laravel-architex/architecture-generator:dev-main

# Publish configuration
php artisan vendor:publish --tag=architex-config
```

## 🧪 Testing

### Test Coverage

The test suite covers:

- ✅ **Architecture Generation**: All pattern generation methods
- ✅ **Template Engine**: Stub rendering and variable replacement
- ✅ **Artisan Commands**: Command execution and output
- ✅ **File Generation**: Directory and file creation
- ✅ **Configuration**: Config loading and merging

### Running Tests

```bash
# Run all tests
./run-tests.sh all

# Run specific test suites
./run-tests.sh unit
./run-tests.sh integration
./run-tests.sh feature

# Run with coverage
./run-tests.sh coverage
```

### Test Commands

```bash
# Test Repository Pattern
php artisan make:repository User

# Test Service Layer
php artisan make:service User

# Test DDD Structure
php artisan make:ddd UserManagement

# Test CQRS Pattern
php artisan make:cqrs CreateUser

# Test Event Bus
php artisan make:event UserCreated

# Test Modular Architecture
php artisan architex:modular UserManagement
```

### Verify Generated Files

```bash
# Check generated files
ls -la app/Repositories/ app/Services/ app/Domain/UserManagement/

# Verify file structure
tree app/Repositories/ app/Services/ app/Domain/UserManagement/ -I "*.git"
```

### API Integration Testing

```bash
# Start development server
php artisan serve

# Test health check endpoint
curl http://localhost:8000/api/health

# Test Repository Pattern API
curl http://localhost:8000/api/architex-test/users/1

# Test Service Layer API
curl http://localhost:8000/api/architex-test/users

# Test CQRS Pattern API
curl -X POST http://localhost:8000/api/architex-test/cqrs-test \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","description":"Test Description"}'
```

### Comprehensive Test Script

```bash
# Run comprehensive test
php artisan architex:test
```

**Expected Output:**
```
🚀 Laravel Architex Test Script
================================

1. Testing Service Layer Pattern:
---------------------------------
✅ UserService instantiated successfully
✅ getAll() method works: 0 users found
✅ Service Layer Pattern: PASSED

2. Testing Repository Pattern:
------------------------------
✅ UserRepositoryInterface resolved successfully
✅ all() method works: 0 users found
✅ Repository Pattern: PASSED

3. Testing CQRS Pattern:
-----------------------
✅ CreateUserCommand created successfully
✅ Command data: {"name":"Test User","description":"Test Description","data":{...}}
✅ CQRS Pattern: PASSED

4. Verifying Generated File Structure:
--------------------------------------
✅ app/Repositories/Interfaces/UserRepositoryInterface.php exists
✅ app/Repositories/UserRepository.php exists
✅ app/Services/UserService.php exists
✅ app/Commands/CreateUserCommand.php exists
✅ app/Queries/CreateUserQuery.php exists
✅ app/Handlers/CreateUserHandler.php exists
✅ app/Domain/UserManagement/Entities/UserManagementEntities.php exists
✅ app/Application/UserManagement/Services/UserManagementServices.php exists
✅ app/Infrastructure/UserManagement/Repositories/UserManagementRepositories.php exists
✅ app/UI/UserManagement/Controllers/UserManagementControllers.php exists
✅ All generated files exist

5. Testing Artisan Commands:
---------------------------
✅ make:repository command available
✅ make:service command available
✅ make:ddd command available
✅ make:cqrs command available
✅ make:event command available
✅ All Artisan commands are registered

📊 Test Summary:
================
✅ Service Layer Pattern: Working
✅ Repository Pattern: Working
✅ CQRS Pattern: Working
✅ DDD Structure: Generated
✅ Event Bus: Available
✅ Artisan Commands: Registered
✅ File Generation: Successful

🎉 Laravel Architex is working perfectly!
```

## 📊 Test Results Summary

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

### Detailed Test Coverage

- ✅ **Artisan Commands**: All command functionality
- ✅ **Error Handling**: Exception scenarios
- ✅ **File System Operations**: Directory and file creation
- ✅ **Template Rendering**: Variable replacement and stub processing
- ✅ **Configuration Loading**: Config merging and validation
- ✅ **Service Provider Registration**: Auto-registration functionality

## 🔧 Development Workflow

### Adding New Architecture Patterns

1. **Create Command Class**
   ```bash
   # Create new command in src/Console/Commands/
   ```

2. **Add Template Files**
   ```bash
   # Add stub files in stubs/
   ```

3. **Update Service Provider**
   ```php
   // Register command in ArchitexServiceProvider
   ```

4. **Update Configuration**
   ```php
   // Add pattern config in config/architex.php
   ```

5. **Write Tests**
   ```bash
   # Create test files in tests/
   ```

### Code Style

```bash
# Check code style
./vendor/bin/phpcs src tests

# Fix code style
./vendor/bin/phpcbf src tests
```

### Building Package

```bash
# Build for distribution
composer build

# Create release
git tag v1.0.0
git push origin v1.0.0
```

## 🐛 Debugging

### Common Issues

1. **Command not found**
   - Check if command is registered in ServiceProvider
   - Verify namespace and class name

2. **Template not found**
   - Check stub file path
   - Verify template engine configuration

3. **File generation fails**
   - Check directory permissions
   - Verify file system operations

### Debug Commands

```bash
# Enable debug mode
APP_DEBUG=true php artisan make:repository User

# Check command list
php artisan list | grep architex

# Check config
php artisan config:show architex
```

## 📝 Contributing

### Pull Request Process

1. Fork the repository
2. Create feature branch
3. Make changes
4. Add tests
5. Update documentation
6. Submit pull request

### Code Standards

- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Add type hints and return types
- Use meaningful commit messages

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [PHPUnit Testing](https://phpunit.de/)
- [Composer Documentation](https://getcomposer.org/doc/)
- [PSR Standards](https://www.php-fig.org/psr/)

---

For more information, see the main [README.md](README.md) file. 