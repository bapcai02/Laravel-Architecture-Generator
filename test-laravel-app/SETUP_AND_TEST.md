# Laravel Architex Setup & Test Guide

## 🚀 Quick Setup

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

### 3. Verify Installation

```bash
# Check if commands are registered
php artisan list | grep make

# Expected output:
# make:repository        Create a new repository interface and implementation
# make:service           Create a new service class
# make:ddd               Create a new DDD module structure with all layers
# make:cqrs              Create a new CQRS structure with commands, queries and handlers
# make:event             Create a new event class
```

## 🧪 Testing Commands

### Test Repository Pattern

```bash
php artisan make:repository User
```

**Expected Output:**
```
Creating repository for: User
Repository created successfully!
Created files:
  - app/Repositories/Interfaces/UserRepositoryInterface.php
  - app/Repositories/UserRepository.php

Next steps:
1. Register the repository in your service provider
2. Implement the repository methods
3. Use dependency injection in your services
```

### Test Service Layer

```bash
php artisan make:service User
```

**Expected Output:**
```
Creating service: User
Service created successfully!
  - app/Services/UserService.php
```

### Test DDD Structure

```bash
php artisan make:ddd UserManagement
```

**Expected Output:**
```
Creating DDD module: UserManagement
DDD module created successfully!
Created files:
  - app/Domain/UserManagement/Entities/UserManagementEntities.php
  - app/Domain/UserManagement/Repositories/UserManagementRepositories.php
  - app/Domain/UserManagement/Services/UserManagementServices.php
  - app/Domain/UserManagement/Events/UserManagementEvents.php
  - app/Domain/UserManagement/Exceptions/UserManagementExceptions.php
  - app/Application/UserManagement/Services/UserManagementServices.php
  - app/Application/UserManagement/Commands/UserManagementCommands.php
  - app/Application/UserManagement/Queries/UserManagementQueries.php
  - app/Application/UserManagement/Handlers/UserManagementHandlers.php
  - app/Infrastructure/UserManagement/Repositories/UserManagementRepositories.php
  - app/Infrastructure/UserManagement/Services/UserManagementServices.php
  - app/Infrastructure/UserManagement/Persistence/UserManagementPersistence.php
  - app/Infrastructure/UserManagement/External/UserManagementExternal.php
  - app/UI/UserManagement/Controllers/UserManagementControllers.php
  - app/UI/UserManagement/Requests/UserManagementRequests.php
  - app/UI/UserManagement/Resources/UserManagementResources.php
  - app/UI/UserManagement/Middleware/UserManagementMiddleware.php

DDD Module Structure:
├── Domain/
│   ├── Entities/
│   ├── Repositories/
│   ├── Services/
│   ├── Events/
│   └── Exceptions/
├── Application/
│   ├── Services/
│   ├── Commands/
│   ├── Queries/
│   └── Handlers/
├── Infrastructure/
│   ├── Repositories/
│   ├── Services/
│   ├── Persistence/
│   └── External/
└── UI/
    ├── Controllers/
    ├── Requests/
    ├── Resources/
    └── Middleware/
```

### Test CQRS Pattern

```bash
php artisan make:cqrs CreateUser
```

**Expected Output:**
```
Creating CQRS structure: CreateUser
CQRS structure created successfully!
  - app/Commands/CreateUserCommand.php
  - app/Queries/CreateUserQuery.php
  - app/Handlers/CreateUserHandler.php
```

### Test Event Bus

```bash
php artisan make:event UserCreated
```

**Expected Output:**
```
Event created successfully.
```

## 🔧 IDE Extensions for Better Development

### VS Code Extensions

```json
{
  "recommendations": [
    "bmewburn.vscode-intelephense-client",
    "onecentlin.laravel-blade",
    "onecentlin.laravel5-snippets",
    "amiralizadeh9480.laravel-extra-intellisense",
    "ryannaddy.laravel-artisan",
    "ms-vscode.vscode-json",
    "bradlc.vscode-tailwindcss",
    "esbenp.prettier-vscode",
    "ms-vscode.vscode-php-debug",
    "neilbrayfield.php-docblocker",
    "junstyle.php-cs-fixer",
    "mehedidracula.php-namespace-resolver",
    "formulahendry.auto-rename-tag",
    "ms-vscode.vscode-typescript-next"
  ]
}
```

### Install VS Code Extensions

```bash
# Create .vscode/extensions.json
mkdir -p .vscode
cat > .vscode/extensions.json << 'EOF'
{
  "recommendations": [
    "bmewburn.vscode-intelephense-client",
    "onecentlin.laravel-blade",
    "onecentlin.laravel5-snippets",
    "amiralizadeh9480.laravel-extra-intellisense",
    "ryannaddy.laravel-artisan",
    "ms-vscode.vscode-json",
    "bradlc.vscode-tailwindcss",
    "esbenp.prettier-vscode",
    "ms-vscode.vscode-php-debug",
    "neilbrayfield.php-docblocker",
    "junstyle.php-cs-fixer",
    "mehedidracula.php-namespace-resolver",
    "formulahendry.auto-rename-tag",
    "ms-vscode.vscode-typescript-next"
  ]
}
EOF
```

### VS Code Settings

```json
{
  "php.validate.executablePath": "/usr/bin/php",
  "php.suggest.basic": false,
  "intelephense.files.maxSize": 5000000,
  "intelephense.environment.includePaths": [
    "vendor/laravel/framework/src",
    "vendor/laravel/framework/src/Illuminate"
  ],
  "intelephense.stubs": [
    "apache",
    "bcmath",
    "bz2",
    "calendar",
    "com_dotnet",
    "Core",
    "ctype",
    "curl",
    "date",
    "dba",
    "dom",
    "enchant",
    "exif",
    "FFI",
    "fileinfo",
    "filter",
    "fpm",
    "ftp",
    "gd",
    "gettext",
    "gmp",
    "hash",
    "iconv",
    "imap",
    "intl",
    "json",
    "ldap",
    "libxml",
    "mbstring",
    "meta",
    "mysqli",
    "oci8",
    "odbc",
    "openssl",
    "pcntl",
    "pcre",
    "PDO",
    "pdo_ibm",
    "pdo_mysql",
    "pdo_pgsql",
    "pdo_sqlite",
    "pgsql",
    "Phar",
    "posix",
    "pspell",
    "readline",
    "Reflection",
    "session",
    "shmop",
    "SimpleXML",
    "snmp",
    "soap",
    "sockets",
    "sodium",
    "SPL",
    "sqlite3",
    "standard",
    "superglobals",
    "sysvmsg",
    "sysvsem",
    "sysvshm",
    "tidy",
    "tokenizer",
    "xml",
    "xmlreader",
    "xmlrpc",
    "xmlwriter",
    "xsl",
    "Zend OPcache",
    "zip",
    "zlib"
  ]
}
```

## 🧪 Comprehensive Testing

### Run All Tests

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

### Verify Generated Files

```bash
# Check repository files
ls -la app/Repositories/

# Check service files
ls -la app/Services/

# Check DDD structure
ls -la app/Domain/UserManagement/

# Check CQRS files
ls -la app/Commands/ app/Queries/ app/Handlers/
```

## 🔍 Debugging

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

## 📊 Test Results

After running all tests, you should see:

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

## 🎯 Next Steps

1. **Customize Templates**: Edit stub files in `stubs/architex/`
2. **Add More Patterns**: Extend with new architecture patterns
3. **Integration Testing**: Add comprehensive tests
4. **Documentation**: Create team documentation
5. **Production Use**: Deploy to real projects

---

**Laravel Architex** - Ready for production use! 🚀 