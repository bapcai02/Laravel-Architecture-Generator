# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release of Laravel Architex
- Support for Repository Pattern generation
- Support for Service Layer generation
- Support for DDD (Domain Driven Design) structure generation
- Support for CQRS (Command Query Responsibility Segregation) generation
- Support for Event Bus generation
- Template engine with customizable stub files
- Artisan commands for all architecture patterns
- Configuration system with `architex.php` config file
- Auto registration system for service providers
- Comprehensive documentation and examples
- Unit tests for core functionality

### Features
- `make:repository` command to generate repository interface and implementation
- `make:service` command to generate service classes
- `make:ddd` command to generate complete DDD module structure
- `make:cqrs` command to generate CQRS commands, queries and handlers
- `make:command` command to generate individual CQRS commands
- `make:query` command to generate individual CQRS queries
- `make:event` command to generate events and listeners
- Configurable naming conventions
- Customizable stub templates
- Support for multiple Laravel versions (10.x, 11.x)

### Architecture Patterns Supported
- **Repository Pattern**: Interface + Implementation with standard CRUD methods
- **Service Layer**: Business logic services with common operations
- **DDD**: Complete 4-layer structure (Domain, Application, Infrastructure, UI)
- **CQRS**: Commands, Queries, and their respective Handlers
- **Event Bus**: Events and Listeners with Laravel's event system

### Configuration Options
- Customizable paths and namespaces for each pattern
- Naming convention configuration (pascal, camel, snake, kebab)
- Template variable customization
- Auto registration settings

## [1.0.0] - 2024-01-01

### Added
- Initial release
- Core architecture generation functionality
- All major architecture patterns support
- Complete documentation and examples 