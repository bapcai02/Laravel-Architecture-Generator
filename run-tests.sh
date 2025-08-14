#!/bin/bash

# Laravel Architex Test Runner
# This script provides easy commands to run tests with different options

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

# Check if PHPUnit is available
check_phpunit() {
    if ! command -v ./vendor/bin/phpunit &> /dev/null; then
        print_error "PHPUnit not found. Please run 'composer install' first."
        exit 1
    fi
}

# Function to run tests
run_tests() {
    local args="$@"
    print_status "Running tests with arguments: $args"
    ./vendor/bin/phpunit $args
}

# Function to show help
show_help() {
    echo "Laravel Architex Test Runner"
    echo ""
    echo "Usage: $0 [COMMAND] [OPTIONS]"
    echo ""
    echo "Commands:"
    echo "  all              Run all tests"
    echo "  unit             Run unit tests only"
    echo "  integration      Run integration tests only"
    echo "  coverage         Run tests with coverage report"
    echo "  specific         Run specific test file or method"
    echo "  watch            Run tests in watch mode (requires fswatch)"
    echo "  help             Show this help message"
    echo ""
    echo "Options:"
    echo "  --verbose        Verbose output"
    echo "  --debug          Debug output"
    echo "  --stop-on-failure Stop on first failure"
    echo ""
    echo "Examples:"
    echo "  $0 all"
    echo "  $0 unit --verbose"
    echo "  $0 specific tests/ArchitectureGeneratorTest.php"
    echo "  $0 specific --filter test_can_generate_repository"
    echo "  $0 coverage"
}

# Main script logic
case "${1:-all}" in
    "all")
        check_phpunit
        print_status "Running all tests..."
        run_tests "${@:2}"
        print_success "All tests completed!"
        ;;
    "unit")
        check_phpunit
        print_status "Running unit tests..."
        run_tests tests/ArchitectureGeneratorTest.php tests/TemplateEngineTest.php "${@:2}"
        print_success "Unit tests completed!"
        ;;
    "integration")
        check_phpunit
        print_status "Running integration tests..."
        run_tests tests/CommandTest.php "${@:2}"
        print_success "Integration tests completed!"
        ;;
    "coverage")
        check_phpunit
        print_status "Running tests with coverage report..."
        run_tests --coverage-html coverage/ --coverage-text "${@:2}"
        print_success "Coverage report generated in coverage/ directory!"
        ;;
    "specific")
        check_phpunit
        if [ -z "$2" ]; then
            print_error "Please specify a test file or method to run."
            echo "Example: $0 specific tests/ArchitectureGeneratorTest.php"
            exit 1
        fi
        print_status "Running specific test: $2"
        run_tests "${@:2}"
        print_success "Specific test completed!"
        ;;
    "watch")
        if ! command -v fswatch &> /dev/null; then
            print_error "fswatch is required for watch mode. Please install it first."
            print_warning "On macOS: brew install fswatch"
            print_warning "On Ubuntu: sudo apt-get install fswatch"
            exit 1
        fi
        check_phpunit
        print_status "Starting test watch mode..."
        print_warning "Press Ctrl+C to stop watching"
        fswatch -o src/ tests/ | while read f; do
            print_status "File changed, running tests..."
            run_tests
            print_success "Tests completed!"
        done
        ;;
    "help"|"-h"|"--help")
        show_help
        ;;
    *)
        print_error "Unknown command: $1"
        echo ""
        show_help
        exit 1
        ;;
esac 