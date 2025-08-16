#!/bin/bash

# Laravel Architex GitHub Actions Local Test Script
# This script simulates the GitHub Actions workflows locally

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to show help
show_help() {
    echo "Laravel Architex GitHub Actions Local Test Script"
    echo ""
    echo "Usage: $0 [COMMAND]"
    echo ""
    echo "Commands:"
    echo "  all              Run all GitHub Actions checks"
    echo "  comments         Check for Vietnamese comments (check-comments.yml)"
    echo "  quality          Run code quality checks (code-quality.yml)"
    echo "  architectures    Test all architecture patterns (test-architectures.yml)"
    echo "  release          Simulate release process (release.yml)"
    echo "  help             Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0 all"
    echo "  $0 comments"
    echo "  $0 quality"
    echo "  $0 architectures"
    echo "  $0 release"
}

# Check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."
    
    if ! command -v php &> /dev/null; then
        print_error "PHP not found. Please install PHP 7.4+ first."
        exit 1
    fi
    
    if ! command -v composer &> /dev/null; then
        print_error "Composer not found. Please install Composer first."
        exit 1
    fi
    
    if ! command -v ./vendor/bin/phpunit &> /dev/null; then
        print_error "PHPUnit not found. Please run 'composer install' first."
        exit 1
    fi
    
    print_success "All prerequisites met"
}

# Check for Vietnamese comments (simulates check-comments.yml)
check_comments() {
    print_status "Running Check Comments Language workflow..."
    
    VIETNAMESE_PATTERN='[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]'
    
    # Check PHP files
    print_status "Checking PHP files..."
    VIETNAMESE_PHP=$(find . -name "*.php" -type f -not -path "./vendor/*" -not -path "./test-laravel-app/*" -exec grep -l "$VIETNAMESE_PATTERN" {} \; 2>/dev/null || true)
    
    if [ -n "$VIETNAMESE_PHP" ]; then
        print_error "Found Vietnamese comments in PHP files:"
        echo "$VIETNAMESE_PHP"
        exit 1
    else
        print_success "No Vietnamese comments found in PHP files"
    fi
    
    # Check documentation files
    print_status "Checking documentation files..."
    VIETNAMESE_DOCS=$(find . -name "*.md" -o -name "*.txt" -o -name "*.rst" | grep -v "./vendor/" | grep -v "./test-laravel-app/" | xargs grep -l "$VIETNAMESE_PATTERN" 2>/dev/null || true)
    
    if [ -n "$VIETNAMESE_DOCS" ]; then
        print_error "Found Vietnamese comments in documentation files:"
        echo "$VIETNAMESE_DOCS"
        exit 1
    else
        print_success "No Vietnamese comments found in documentation files"
    fi
    
    # Check configuration files
    print_status "Checking configuration files..."
    VIETNAMESE_CONFIGS=$(find . -name "*.json" -o -name "*.xml" -o -name "*.yml" -o -name "*.yaml" | grep -v "./vendor/" | grep -v "./test-laravel-app/" | xargs grep -l "$VIETNAMESE_PATTERN" 2>/dev/null || true)
    
    if [ -n "$VIETNAMESE_CONFIGS" ]; then
        print_error "Found Vietnamese comments in configuration files:"
        echo "$VIETNAMESE_CONFIGS"
        exit 1
    else
        print_success "No Vietnamese comments found in configuration files"
    fi
    
    # Check shell scripts
    print_status "Checking shell scripts..."
    VIETNAMESE_SCRIPTS=$(find . -name "*.sh" -o -name "*.bash" | grep -v "./vendor/" | grep -v "./test-laravel-app/" | grep -v "./test-github-actions.sh" | xargs grep -l "$VIETNAMESE_PATTERN" 2>/dev/null || true)
    
    if [ -n "$VIETNAMESE_SCRIPTS" ]; then
        print_error "Found Vietnamese comments in shell scripts:"
        echo "$VIETNAMESE_SCRIPTS"
        exit 1
    else
        print_success "No Vietnamese comments found in shell scripts"
    fi
    
    print_success "All comment language checks passed!"
}

# Run code quality checks (simulates code-quality.yml)
check_quality() {
    print_status "Running Code Quality Check workflow..."
    
    # Validate composer.json
    print_status "Validating composer.json..."
    composer validate --strict
    
    # Check PHP syntax
    print_status "Checking PHP syntax..."
    find src/ tests/ -name "*.php" -exec php -l {} \;
    
    # Run PHPUnit tests
    print_status "Running PHPUnit tests..."
    ./vendor/bin/phpunit --coverage-clover=coverage.xml
    
    # Check for TODO comments
    print_status "Checking for TODO comments..."
    TODO_COUNT=$(grep -r "TODO" src/ tests/ | wc -l)
    if [ $TODO_COUNT -gt 0 ]; then
        print_warning "Found $TODO_COUNT TODO comments:"
        grep -r "TODO" src/ tests/
    else
        print_success "No TODO comments found"
    fi
    
    # Check for FIXME comments
    print_status "Checking for FIXME comments..."
    FIXME_COUNT=$(grep -r "FIXME" src/ tests/ | wc -l)
    if [ $FIXME_COUNT -gt 0 ]; then
        print_warning "Found $FIXME_COUNT FIXME comments:"
        grep -r "FIXME" src/ tests/
    else
        print_success "No FIXME comments found"
    fi
    
    # Check for debug statements
    print_status "Checking for debug statements..."
    DEBUG_COUNT=$(grep -r "var_dump\|dd\|dump" src/ | wc -l)
    if [ $DEBUG_COUNT -gt 0 ]; then
        print_error "Found $DEBUG_COUNT debug statements in source code:"
        grep -r "var_dump\|dd\|dump" src/
        exit 1
    else
        print_success "No debug statements found in source code"
    fi
    
    # Check file permissions
    print_status "Checking file permissions..."
    find . -name "*.sh" -exec chmod +x {} \;
    print_success "Shell scripts are executable"
    
    # Check for missing files
    print_status "Checking for missing files..."
    ESSENTIAL_FILES=(
        "composer.json"
        "README.md"
        "LICENSE"
        "src/ArchitexServiceProvider.php"
        "config/architex.php"
    )
    
    for file in "${ESSENTIAL_FILES[@]}"; do
        if [ ! -f "$file" ]; then
            print_error "Missing essential file: $file"
            exit 1
        fi
    done
    
    print_success "All essential files present"
    print_success "Code quality check completed!"
}

# Test architecture patterns (simulates test-architectures.yml)
test_architectures() {
    print_status "Running Test Architecture Patterns workflow..."
    
    # Create test Laravel app
    print_status "Creating test Laravel application..."
    if [ -d "test-laravel-app" ]; then
        print_warning "test-laravel-app already exists. Removing..."
        rm -rf test-laravel-app
    fi
    
    composer create-project laravel/laravel test-laravel-app --prefer-dist --no-interaction
    
    # Install Laravel Architex in test app
    print_status "Installing Laravel Architex in test app..."
    cd test-laravel-app
    composer config repositories.laravel-architex path ../
    composer require laravel-architex/architecture-generator:dev-main --no-interaction
    
    # Publish configuration
    print_status "Publishing configuration..."
    php artisan vendor:publish --tag=architex-config
    
    # Test all architecture patterns
    print_status "Testing all architecture patterns..."
    
    # Test Repository Pattern
    print_status "Testing Repository Pattern..."
    php artisan make:repository TestRepo --force
    if [ ! -f "app/Repositories/Interfaces/TestRepoRepositoryInterface.php" ]; then
        print_error "Repository interface not created"
        exit 1
    fi
    if [ ! -f "app/Repositories/TestRepoRepository.php" ]; then
        print_error "Repository implementation not created"
        exit 1
    fi
    print_success "Repository Pattern test passed"
    
    # Test Service Layer
    print_status "Testing Service Layer..."
    php artisan make:service TestService --force
    if [ ! -f "app/Services/TestServiceService.php" ]; then
        print_error "Service not created"
        exit 1
    fi
    print_success "Service Layer test passed"
    
    # Test DDD Structure
    print_status "Testing DDD Structure..."
    php artisan make:ddd TestDDD --force
    if [ ! -d "app/Domain/TestDDD" ]; then
        print_error "DDD structure not created"
        exit 1
    fi
    print_success "DDD Structure test passed"
    
    # Test CQRS Pattern
    print_status "Testing CQRS Pattern..."
    php artisan make:cqrs TestCQRS --force
    if [ ! -f "app/Commands/TestCQRSCommand.php" ]; then
        print_error "CQRS Command not created"
        exit 1
    fi
    if [ ! -f "app/Queries/TestCQRSQuery.php" ]; then
        print_error "CQRS Query not created"
        exit 1
    fi
    if [ ! -f "app/Handlers/TestCQRSHandler.php" ]; then
        print_error "CQRS Handler not created"
        exit 1
    fi
    print_success "CQRS Pattern test passed"
    
    # Test Event Bus
    print_status "Testing Event Bus..."
    php artisan make:event TestEvent --force
    if [ ! -f "app/Events/TestEvent.php" ]; then
        print_error "Event not created"
        exit 1
    fi
    if [ ! -f "app/Listeners/TestEventListener.php" ]; then
        print_error "Listener not created"
        exit 1
    fi
    print_success "Event Bus test passed"
    
    # Test Modular Architecture
    print_status "Testing Modular Architecture..."
    php artisan architex:modular TestModular --with-tests --with-migrations --with-seeders --with-routes --with-config --force
    if [ ! -d "app/Modules/TestModular" ]; then
        print_error "Modular structure not created"
        exit 1
    fi
    if [ ! -f "app/Modules/TestModular/Controllers/TestModularController.php" ]; then
        print_error "Modular controller not created"
        exit 1
    fi
    if [ ! -f "app/Modules/TestModular/Models/TestModular.php" ]; then
        print_error "Modular model not created"
        exit 1
    fi
    print_success "Modular Architecture test passed"
    
    # Test Hexagonal Architecture
    print_status "Testing Hexagonal Architecture..."
    php artisan architex:hexagonal TestHexagonal --with-tests --with-migrations --with-routes --force
    if [ ! -d "app/Hexagonal/TestHexagonal" ]; then
        print_error "Hexagonal structure not created"
        exit 1
    fi
    if [ ! -f "app/Hexagonal/TestHexagonal/Domain/Entities/TestHexagonal.php" ]; then
        print_error "Hexagonal entity not created"
        exit 1
    fi
    if [ ! -f "app/Hexagonal/TestHexagonal/Application/Services/TestHexagonalApplicationService.php" ]; then
        print_error "Hexagonal application service not created"
        exit 1
    fi
    print_success "Hexagonal Architecture test passed"
    
    # Verify generated files syntax
    print_status "Verifying generated files syntax..."
    find app/ -name "*.php" -exec php -l {} \;
    print_success "All generated files have valid PHP syntax"
    
    # Check for unreplaced template variables
    print_status "Checking for unreplaced template variables..."
    UNREPLACED_VARS=$(find app/ -name "*.php" -exec grep -l "{{.*}}" {} \; 2>/dev/null || true)
    
    if [ -n "$UNREPLACED_VARS" ]; then
        print_error "Found files with unreplaced template variables:"
        echo "$UNREPLACED_VARS"
        echo ""
        echo "Template variables that were not replaced:"
        find app/ -name "*.php" -exec grep -H "{{.*}}" {} \; 2>/dev/null || true
        exit 1
    else
        print_success "No unreplaced template variables found"
    fi
    
    cd ..
    print_success "All architecture pattern tests completed successfully!"
}

# Simulate release process (simulates release.yml)
simulate_release() {
    print_status "Running Release Package workflow..."
    
    # Validate composer.json
    print_status "Validating composer.json..."
    composer validate --strict
    
    # Run tests
    print_status "Running tests before release..."
    ./vendor/bin/phpunit
    
    # Check for Vietnamese comments
    print_status "Final check for Vietnamese comments..."
    VIETNAMESE_PATTERN='[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]'
    VIETNAMESE_FILES=$(find . -name "*.php" -o -name "*.md" -o -name "*.txt" -o -name "*.json" -o -name "*.xml" -o -name "*.yml" -o -name "*.yaml" -o -name "*.sh" | grep -v "./vendor/" | grep -v "./test-laravel-app/" | grep -v "./test-github-actions.sh" | xargs grep -l "$VIETNAMESE_PATTERN" 2>/dev/null || true)
    
    if [ -n "$VIETNAMESE_FILES" ]; then
        print_error "Found Vietnamese comments in files:"
        echo "$VIETNAMESE_FILES"
        exit 1
    fi
    
    print_success "No Vietnamese comments found"
    
    # Build package
    print_status "Building package..."
    composer install --no-dev --optimize-autoloader
    
    # Create package archive
    PACKAGE_NAME="laravel-architex-$(date +%Y%m%d-%H%M%S).tar.gz"
    tar -czf "$PACKAGE_NAME" \
        --exclude='.git' \
        --exclude='.github' \
        --exclude='test-laravel-app' \
        --exclude='vendor' \
        --exclude='node_modules' \
        --exclude='*.log' \
        --exclude='*.cache' \
        .
    
    print_success "Package archive created: $PACKAGE_NAME"
    print_success "Release simulation completed successfully!"
}

# Main script logic
case "${1:-all}" in
    "all")
        check_prerequisites
        check_comments
        check_quality
        test_architectures
        simulate_release
        print_success "All GitHub Actions workflows simulated successfully!"
        ;;
    "comments")
        check_prerequisites
        check_comments
        ;;
    "quality")
        check_prerequisites
        check_quality
        ;;
    "architectures")
        check_prerequisites
        test_architectures
        ;;
    "release")
        check_prerequisites
        simulate_release
        ;;
    "help"|"-h"|"--help")
        show_help
        ;;
    *)
        print_error "Unknown command: $1"
        show_help
        exit 1
        ;;
esac 