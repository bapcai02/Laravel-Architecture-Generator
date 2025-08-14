# Testing Guide for Laravel Architex

This guide explains how to run tests for the Laravel Architex package and what each test covers.

## 🧪 Test Structure

```
tests/
├── TestCase.php                    # Base test case with Laravel setup
├── ArchitectureGeneratorTest.php   # Tests for core generation logic
├── TemplateEngineTest.php          # Tests for template rendering
└── CommandTest.php                 # Tests for Artisan commands
```

## 🚀 Running Tests

### Prerequisites

1. Install dependencies:
```bash
composer install
```

2. Make sure you have PHPUnit installed:
```bash
composer require --dev phpunit/phpunit
```

### Run All Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run with verbose output
./vendor/bin/phpunit --verbose

# Run with coverage report
./vendor/bin/phpunit --coverage-html coverage/
```

### Run Specific Test Files

```bash
# Test only the architecture generator
./vendor/bin/phpunit tests/ArchitectureGeneratorTest.php

# Test only template engine
./vendor/bin/phpunit tests/TemplateEngineTest.php

# Test only commands
./vendor/bin/phpunit tests/CommandTest.php
```

### Run Specific Test Methods

```bash
# Run a specific test method
./vendor/bin/phpunit --filter test_can_generate_repository

# Run tests matching a pattern
./vendor/bin/phpunit --filter "test_can_generate_*"
```

## 📋 Test Coverage

### ArchitectureGeneratorTest

Tests the core functionality of generating different architecture patterns:

- ✅ **Repository Pattern Generation**
  - Creates interface and implementation files
  - Verifies correct file paths and content
  - Tests naming conventions

- ✅ **Service Layer Generation**
  - Creates service class files
  - Verifies correct namespace and class names
  - Tests basic service methods

- ✅ **DDD Structure Generation**
  - Creates complete DDD module structure
  - Verifies all layers (Domain, Application, Infrastructure, UI)
  - Tests subdirectory creation

- ✅ **CQRS Generation**
  - Creates commands, queries, and handlers
  - Verifies correct file structure
  - Tests command and query patterns

- ✅ **Event Bus Generation**
  - Creates events and listeners
  - Verifies Laravel event system integration
  - Tests event-listener relationships

- ✅ **Name Formatting**
  - Tests naming convention application
  - Verifies suffix handling
  - Tests case conversion

### TemplateEngineTest

Tests the template rendering system:

- ✅ **Template Rendering**
  - Tests variable replacement in templates
  - Verifies correct namespace and class name insertion
  - Tests template file loading

- ✅ **Default Variables**
  - Tests merging of default configuration variables
  - Verifies author, year, and namespace variables
  - Tests variable precedence

- ✅ **Error Handling**
  - Tests missing stub file exceptions
  - Verifies proper error messages
  - Tests invalid template paths

- ✅ **Stub Discovery**
  - Tests available stub file listing
  - Verifies custom and default stub paths
  - Tests stub file filtering

### CommandTest

Tests Artisan command functionality:

- ✅ **Repository Command**
  - Tests `make:repository` command execution
  - Verifies command output and exit codes
  - Tests force and model options

- ✅ **Service Command**
  - Tests `make:service` command execution
  - Verifies service generation
  - Tests command options

- ✅ **DDD Command**
  - Tests `make:ddd` command execution
  - Verifies module structure creation
  - Tests layer-specific generation

- ✅ **Command Options**
  - Tests `--force` option for overwriting files
  - Tests `--model` option for custom model names
  - Tests `--layers` option for DDD

## 🔧 Test Configuration

### PHPUnit Configuration

The `phpunit.xml` file configures:

```xml
<phpunit>
    <testsuites>
        <testsuite name="Laravel Architex Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory suffix=".php">src/</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="QUEUE_DRIVER" value="sync"/>
    </php>
</phpunit>
```

### Test Environment Setup

Each test class sets up:

1. **Configuration Mocking**
   ```php
   Config::set('architex', [
       'patterns' => [...],
       'naming' => [...],
       'templates' => [...],
   ]);
   ```

2. **Service Instantiation**
   ```php
   $this->filesystem = new Filesystem();
   $this->generator = new ArchitectureGenerator($filesystem, $templateEngine);
   ```

3. **Cleanup**
   ```php
   protected function tearDown(): void
   {
       // Clean up generated files
       $this->filesystem->deleteDirectory(app_path('Repositories'));
       // ... other directories
   }
   ```

## 🧹 Test Cleanup

Tests automatically clean up generated files to prevent interference:

- Repository files and directories
- Service files and directories
- DDD module structures
- CQRS commands and queries
- Event and listener files

## 📊 Test Metrics

### Coverage Goals

- **Line Coverage**: > 90%
- **Branch Coverage**: > 85%
- **Function Coverage**: > 95%

### Performance Targets

- **Test Execution Time**: < 30 seconds for full suite
- **Memory Usage**: < 50MB peak
- **File I/O**: Minimal temporary file creation

## 🐛 Debugging Tests

### Verbose Output

```bash
./vendor/bin/phpunit --verbose --debug
```

### Test Isolation

```bash
# Run single test with isolation
./vendor/bin/phpunit --filter test_can_generate_repository --stop-on-failure
```

### Manual Testing

For manual testing of generated files:

```bash
# Generate files manually
php artisan make:repository User
php artisan make:service User
php artisan make:ddd UserManagement

# Check generated files
ls -la app/Repositories/
ls -la app/Services/
ls -la app/Domain/
```

## 🔄 Continuous Integration

### GitHub Actions Example

```yaml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: ./vendor/bin/phpunit
```

## 📝 Writing New Tests

### Test Naming Convention

- Use descriptive test method names
- Follow pattern: `test_should_do_something_when_condition`
- Group related tests in same class

### Test Structure

```php
public function test_should_generate_repository_when_valid_name_provided()
{
    // Arrange
    $name = 'User';
    
    // Act
    $result = $this->generator->generateRepository($name);
    
    // Assert
    $this->assertIsArray($result);
    $this->assertCount(2, $result);
    $this->assertTrue($this->filesystem->exists($result[0]));
}
```

### Best Practices

1. **Test One Thing**: Each test should verify one specific behavior
2. **Use Descriptive Names**: Test names should explain what is being tested
3. **Arrange-Act-Assert**: Structure tests in three clear sections
4. **Clean Up**: Always clean up generated files in tearDown
5. **Mock Dependencies**: Use mocks for external dependencies
6. **Test Edge Cases**: Include tests for error conditions and edge cases

## 🎯 Test Scenarios

### Happy Path Tests

- ✅ Valid input generates correct files
- ✅ Correct namespaces and class names
- ✅ Proper file structure and content

### Error Handling Tests

- ✅ Invalid input throws appropriate exceptions
- ✅ Missing dependencies handled gracefully
- ✅ File system errors handled properly

### Integration Tests

- ✅ Commands work with Laravel application
- ✅ Service provider registration works
- ✅ Configuration loading works correctly

This testing setup ensures the Laravel Architex package is reliable, maintainable, and works correctly across different environments. 