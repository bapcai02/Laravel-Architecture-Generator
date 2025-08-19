# Laravel Architex Installation Guide

Complete installation guide for Laravel Architex from scratch, including creating a test Laravel application.

## 🚀 Prerequisites

### 1. System Requirements

```bash
# Check PHP version (requires 7.4+)
php --version

# Check Composer
composer --version

# Check Git
git --version
```

### 2. Install PHP & Extensions (Ubuntu/Debian)

```bash
# Update system
sudo apt update

# Install PHP and required extensions
sudo apt install php php-cli php-mbstring php-xml php-curl php-zip php-sqlite3 php-mysql php-bcmath php-intl -y

# Verify installation
php --version
```

### 3. Install Composer

```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verify installation
composer --version
```

## 📦 Package Setup

### 1. Clone Repository

```bash
# Clone the repository
git clone <repository-url>
cd Laravel-Architecture-Generator

# Or if you already have the code
cd /path/to/Laravel-Architecture-Generator
```

### 2. Install Package Dependencies

```bash
# Install package dependencies
composer install

# Install development dependencies
composer install --dev
```

### 3. Run Package Tests

```bash
# Make test script executable
chmod +x run-tests.sh

# Run all tests
./run-tests.sh all

# Run with coverage
./run-tests.sh coverage

# Run specific tests
./run-tests.sh specific tests/ArchitectureGeneratorTest.php
```

## 🧪 Test Laravel Application Setup

### 1. Create Test Laravel App

```bash
# Create new Laravel application
composer create-project laravel/laravel test-laravel-app --prefer-dist

# Navigate to test app
cd test-laravel-app
```

### 2. Install Laravel Architex

```bash
# Add local repository to composer
composer config repositories.laravel-architex path ../

# Install Laravel Architex package
composer require laravel-architex/architecture-generator:dev-main

# Publish configuration and stubs
php artisan vendor:publish --tag=architex-config
```

### 3. Fix Missing Files (If Needed)

```bash
# Make fix script executable
chmod +x fix-missing-files.sh

# Run fix script if you encounter missing file errors
./fix-missing-files.sh
```

### 4. Install VS Code Extensions (Optional)

```bash
# Make extension installer executable
chmod +x install-extensions.sh

# Install recommended VS Code extensions
./install-extensions.sh
```

## 🧪 Testing Commands

### 1. Test All Commands

```bash
# Test Repository Pattern (with service layer)
php artisan make:repository User --service

# Test Service Layer (separate)
php artisan make:service User

# Test DDD Structure
php artisan make:ddd UserManagement

# Test CQRS Pattern
php artisan make:cqrs CreateUser

# Test Event Bus (recommended)
php artisan architex:event-bus UserCreated

# Or using Laravel defaults
php artisan make:event UserCreatedEvent
php artisan make:listener UserCreatedListener --event="App\\Events\\UserCreatedEvent"
```

### 2. Run Comprehensive Test

```bash
# Test all features
php artisan architex:test
```

### 3. Test API Endpoints

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

## 🔧 Configuration

### 1. Customize Templates

```bash
# Edit stub files
nano stubs/architex/repository-interface.stub
nano stubs/architex/service.stub
nano stubs/architex/command.stub
```

### 2. Configure Naming Conventions

```bash
# Edit config file
nano config/architex.php
```

## 📁 Generated Files Structure

After running test commands, you'll have:

```
app/
├── Repositories/
│   ├── Base/
│   │   └── BaseRepository.php          # Trait with common methods
│   ├── Interfaces/
│   │   └── UserRepositoryInterface.php # Repository contract
│   ├── UserRepository.php              # Implementation + trait usage
│   └── RepositoryServiceProvider.php   # Repository bindings
├── Services/
│   ├── Interfaces/
│   │   └── UserServiceInterface.php    # Service contract
│   ├── Implementations/
│   │   └── UserService.php             # Service implementation
│   └── ServiceServiceProvider.php      # Service bindings
├── Domain/UserManagement/
│   ├── Entities/
│   ├── Repositories/
│   ├── Services/
│   ├── Events/
│   └── Exceptions/
├── Application/UserManagement/
├── Infrastructure/UserManagement/
├── UI/UserManagement/
├── Commands/CreateUserCommand.php
├── Queries/CreateUserQuery.php
├── Handlers/CreateUserHandler.php
└── Events/UserCreatedEvent.php
```

## 🚀 Quick Setup Script

### Create setup.sh

```bash
#!/bin/bash

echo "🚀 Laravel Architex Quick Setup"
echo "==============================="

# Check prerequisites
echo "📋 Checking prerequisites..."
php --version || { echo "❌ PHP not found"; exit 1; }
composer --version || { echo "❌ Composer not found"; exit 1; }

# Install package dependencies
echo "📦 Installing package dependencies..."
composer install

# Make test script executable
echo "🔧 Setting up test scripts..."
chmod +x run-tests.sh

# Run package tests
echo "🧪 Running package tests..."
./run-tests.sh all

# Create test Laravel app
echo "🏗️ Creating test Laravel app..."
composer create-project laravel/laravel test-laravel-app --prefer-dist

# Navigate to test app
cd test-laravel-app

# Install Laravel Architex
echo "📦 Installing Laravel Architex..."
composer config repositories.laravel-architex path ../
composer require laravel-architex/architecture-generator:dev-main

# Publish configuration
echo "⚙️ Publishing configuration..."
php artisan vendor:publish --tag=architex-config

# Make scripts executable
chmod +x fix-missing-files.sh
chmod +x install-extensions.sh

# Fix missing files
echo "🔧 Fixing missing files..."
./fix-missing-files.sh

echo "🎉 Setup completed!"
echo ""
echo "Next steps:"
echo "1. cd test-laravel-app"
echo "2. ./install-extensions.sh (optional)"
echo "3. php artisan architex:test"
echo "4. php artisan serve"
```

### Make it executable

```bash
chmod +x setup.sh
./setup.sh
```

## 📊 Expected Test Results

After successful installation, you should see:

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

## 🔍 Troubleshooting

### Common Issues

1. **PHP Version Error**
   ```bash
   # Update composer.json to support PHP 7.4+
   "require": {
       "php": "^7.4|^8.0|^8.1"
   }
   ```

2. **Missing Files Error**
   ```bash
   # Run fix script
   ./fix-missing-files.sh
   ```

3. **Composer Repository Error**
   ```bash
   # Clear composer cache
   composer clear-cache
   
   # Reinstall
   composer install
   ```

4. **Laravel Version Compatibility**
   ```bash
   # Check Laravel version
   php artisan --version
   
   # Update composer.json if needed
   "require": {
       "laravel/framework": "^8.0|^9.0|^10.0|^11.0"
   }
   ```

## 📚 Documentation

- [README.md](README.md) - Main package documentation
- [TESTING.md](TESTING.md) - Testing guide
- [CHANGELOG.md](CHANGELOG.md) - Version history

## 🎯 Next Steps

1. **Customize Templates**: Edit stub files in `stubs/architex/`
2. **Add More Patterns**: Extend with new architecture patterns
3. **Integration Testing**: Add comprehensive tests
4. **Documentation**: Create team documentation
5. **Production Use**: Deploy to real projects

---

**Laravel Architex** - Ready for production use! 🚀 