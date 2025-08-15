#!/bin/bash

echo "🚀 Laravel Architex Quick Setup"
echo "==============================="
echo ""

# Check prerequisites
echo "📋 Checking prerequisites..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP 7.4+ first."
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "❌ Composer not found. Please install Composer first."
    exit 1
fi

echo "✅ PHP version: $(php --version | head -n1)"
echo "✅ Composer version: $(composer --version | head -n1)"
echo ""

# Install package dependencies
echo "📦 Installing package dependencies..."
composer install

if [ $? -ne 0 ]; then
    echo "❌ Failed to install package dependencies"
    exit 1
fi

# Make test script executable
echo "🔧 Setting up test scripts..."
chmod +x run-tests.sh

# Run package tests
echo "🧪 Running package tests..."
./run-tests.sh all

# Continue even if tests fail (for development)
if [ $? -ne 0 ]; then
    echo "⚠️  Package tests had some issues, but continuing..."
fi

# Check if test-laravel-app already exists
if [ -d "test-laravel-app" ]; then
    echo "⚠️  test-laravel-app already exists. Removing..."
    rm -rf test-laravel-app
fi

# Create test Laravel app
echo "🏗️ Creating test Laravel app..."
composer create-project laravel/laravel test-laravel-app --prefer-dist

if [ $? -ne 0 ]; then
    echo "❌ Failed to create Laravel app"
    exit 1
fi

# Navigate to test app
cd test-laravel-app

# Install Laravel Architex
echo "📦 Installing Laravel Architex..."
composer config repositories.laravel-architex path ../
composer require laravel-architex/architecture-generator:dev-develop

if [ $? -ne 0 ]; then
    echo "❌ Failed to install Laravel Architex"
    exit 1
fi

# Publish configuration
echo "⚙️ Publishing configuration..."
php artisan vendor:publish --tag=architex-config

# Make scripts executable
chmod +x fix-missing-files.sh
chmod +x install-extensions.sh

# Fix missing files
echo "🔧 Fixing missing files..."
./fix-missing-files.sh

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "📁 Files created:"
echo "✅ test-laravel-app/ - Laravel test application"
echo "✅ test-laravel-app/.gitignore - Git ignore rules"
echo "✅ test-laravel-app/SETUP_AND_TEST.md - Setup guide"
echo "✅ test-laravel-app/.vscode/ - VS Code configuration"
echo "✅ test-laravel-app/install-extensions.sh - Extension installer"
echo "✅ test-laravel-app/fix-missing-files.sh - Missing files fixer"
echo "✅ test-laravel-app/README.md - Test app documentation"
echo ""
echo "🚀 Next steps:"
echo "1. cd test-laravel-app"
echo "2. ./install-extensions.sh (optional - install VS Code extensions)"
echo "3. php artisan architex:test (test all features)"
echo "4. php artisan serve (start development server)"
echo "5. Visit http://localhost:8000/api/health (test API)"
echo ""
echo "🧪 Test commands:"
echo "- php artisan make:repository User"
echo "- php artisan make:service User"
echo "- php artisan make:ddd UserManagement"
echo "- php artisan make:cqrs CreateUser"
echo "- php artisan make:event UserCreated"
echo ""
echo "📚 Documentation:"
echo "- README.md - Main package documentation"
echo "- INSTALLATION_GUIDE.md - Installation guide"
echo "- TESTING.md - Testing guide"
echo ""
echo "🎯 Laravel Architex is ready for development!" 