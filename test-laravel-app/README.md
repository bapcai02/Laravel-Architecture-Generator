# Laravel Architex Test Application

This is a test Laravel application to demonstrate and test the **Laravel Architex** package functionality.

## 🚀 Quick Start

### 1. Prerequisites

```bash
# Check PHP version (requires 7.4+)
php --version

# Check Composer
composer --version

# Check Laravel
php artisan --version
```

### 2. Install Laravel Architex

```bash
# Install the package
composer require laravel-architex/architecture-generator:dev-main

# Publish configuration and stubs
php artisan vendor:publish --tag=architex-config
```

### 3. Fix Missing Files (If Needed)

```bash
# If you encounter missing file errors, run this script
chmod +x fix-missing-files.sh
./fix-missing-files.sh
```

### 4. Install VS Code Extensions (Optional)

```bash
# Make script executable
chmod +x install-extensions.sh

# Run extension installer
./install-extensions.sh
```

## 🧪 Testing Laravel Architex

### Test All Commands

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
```

### Run Comprehensive Test

```bash
# Test all features
php artisan architex:test
```

### Test API Endpoints

```bash
# Start development server
php artisan serve

# Test health check
curl http://localhost:8000/api/health

# Test repository pattern
curl http://localhost:8000/api/architex-test/users/1

# Test service layer
curl http://localhost:8000/api/architex-test/users

# Test CQRS pattern
curl -X POST http://localhost:8000/api/architex-test/cqrs-test \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","description":"Test Description"}'
```

## 📁 Generated Files Structure

After running the test commands, you'll have:

```
app/
├── Repositories/
│   ├── Interfaces/UserRepositoryInterface.php
│   └── UserRepository.php
├── Services/
│   └── UserService.php
├── Domain/UserManagement/
│   ├── Entities/
│   ├── Repositories/
│   ├── Services/
│   ├── Events/
│   └── Exceptions/
├── Application/UserManagement/
│   ├── Services/
│   ├── Commands/
│   ├── Queries/
│   └── Handlers/
├── Infrastructure/UserManagement/
│   ├── Repositories/
│   ├── Services/
│   ├── Persistence/
│   └── External/
├── UI/UserManagement/
│   ├── Controllers/
│   ├── Requests/
│   ├── Resources/
│   └── Middleware/
├── Commands/CreateUserCommand.php
├── Queries/CreateUserQuery.php
├── Handlers/CreateUserHandler.php
└── Events/UserCreatedEvent.php
```

## 🔧 Configuration

### Customize Templates

Edit files in `stubs/architex/` directory:

```bash
# Edit repository template
nano stubs/architex/repository-interface.stub

# Edit service template
nano stubs/architex/service.stub

# Edit command template
nano stubs/architex/command.stub
```

### Configuration File

Edit `config/architex.php` to customize:

- File paths and namespaces
- Naming conventions
- Architecture patterns
- Auto registration

## 🎯 Architecture Patterns Tested

### 1. Repository Pattern
- Interface + Implementation
- CRUD operations
- Dependency injection
- Service provider binding

### 2. Service Layer
- Business logic services
- Common operations
- Model interactions
- Error handling

### 3. Domain Driven Design (DDD)
- 4-layer architecture
- Domain, Application, Infrastructure, UI
- Complete module structure
- Separation of concerns

### 4. CQRS (Command Query Responsibility Segregation)
- Commands for write operations
- Queries for read operations
- Handlers for business logic
- Event sourcing ready

### 5. Event Bus
- Events and listeners
- Event dispatching
- Asynchronous processing
- Decoupled architecture

## 📊 Test Results

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

## 🔍 Debugging

### Fix Missing Files

```bash
# If you get "Class not found" errors, run this script
./fix-missing-files.sh
```

### Check Package Registration

```bash
# Check if package is discovered
php artisan package:discover

# Check service providers
php artisan config:cache
php artisan route:cache
```

### Check Generated Code

```bash
# View generated repository interface
cat app/Repositories/Interfaces/UserRepositoryInterface.php

# View generated service
cat app/Services/UserService.php

# View generated command
cat app/Commands/CreateUserCommand.php
```

### Check Configuration

```bash
# View published config
cat config/architex.php

# Check stubs directory
ls -la stubs/architex/
```

## 🚀 Production Ready

### Customize Templates

```bash
# Edit stub files
nano stubs/architex/repository-interface.stub
nano stubs/architex/service.stub
nano stubs/architex/command.stub
```

### Configure Naming Conventions

```bash
# Edit config file
nano config/architex.php
```

### Auto Registration

```bash
# Register repositories in service provider
nano app/Providers/RepositoryServiceProvider.php
```

## 📚 Documentation

- [SETUP_AND_TEST.md](SETUP_AND_TEST.md) - Detailed setup and testing guide
- [ARCHITEX_TEST_GUIDE.md](ARCHITEX_TEST_GUIDE.md) - Comprehensive testing guide
- [Main Package README](../README.md) - Main package documentation

## 🎯 Next Steps

1. **Customize Templates**: Edit stub files in `stubs/architex/`
2. **Add More Patterns**: Extend with new architecture patterns
3. **Integration Testing**: Add comprehensive tests
4. **Documentation**: Create team documentation
5. **Production Use**: Deploy to real projects

## 🤝 Contributing

This test application is part of the Laravel Architex package. To contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This test application is part of the Laravel Architex package and is released under the MIT license.

---

**Laravel Architex Test Application** - Successfully tested and ready for production use! 🚀
