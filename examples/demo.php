<?php

/**
 * Laravel Architex Demo
 * 
 * This file demonstrates how to use Laravel Architex to create project structure
 * based on different architecture patterns.
 */

// 1. Repository Pattern Demo
echo "=== Repository Pattern Demo ===\n";
echo "php artisan make:repository User\n";
echo "Result:\n";
echo "- app/Repositories/Interfaces/UserRepositoryInterface.php\n";
echo "- app/Repositories/UserRepository.php\n\n";

// 2. Service Layer Demo
echo "=== Service Layer Demo ===\n";
echo "php artisan make:service User\n";
echo "Result:\n";
echo "- app/Services/UserService.php\n\n";

// 3. DDD Demo
echo "=== DDD (Domain Driven Design) Demo ===\n";
echo "php artisan make:ddd UserManagement\n";
echo "Result:\n";
echo "app/\n";
echo "├── Domain/\n";
echo "│   └── UserManagement/\n";
echo "│       ├── Entities/\n";
echo "│       ├── Repositories/\n";
echo "│       ├── Services/\n";
echo "│       ├── Events/\n";
echo "│       └── Exceptions/\n";
echo "├── Application/\n";
echo "│   └── UserManagement/\n";
echo "│       ├── Services/\n";
echo "│       ├── Commands/\n";
echo "│       ├── Queries/\n";
echo "│       └── Handlers/\n";
echo "├── Infrastructure/\n";
echo "│   └── UserManagement/\n";
echo "│       ├── Repositories/\n";
echo "│       ├── Services/\n";
echo "│       ├── Persistence/\n";
echo "│       └── External/\n";
echo "└── UI/\n";
echo "    └── UserManagement/\n";
echo "        ├── Controllers/\n";
echo "        ├── Requests/\n";
echo "        ├── Resources/\n";
echo "        └── Middleware/\n\n";

// 4. CQRS Demo
echo "=== CQRS Demo ===\n";
echo "php artisan make:cqrs CreateUser\n";
echo "Result:\n";
echo "- app/Commands/CreateUserCommand.php\n";
echo "- app/Queries/GetUserQuery.php\n";
echo "- app/Handlers/CreateUserCommandHandler.php\n";
echo "- app/Handlers/GetUserQueryHandler.php\n\n";

// 5. Event Bus Demo
echo "=== Event Bus Demo ===\n";
echo "php artisan make:event UserCreated\n";
echo "Result:\n";
echo "- app/Events/UserCreatedEvent.php\n";
echo "- app/Listeners/UserCreatedListener.php\n\n";

// 6. Usage examples in Controller
echo "=== Usage Examples in Controller ===\n";
echo "// Repository Pattern\n";
echo "class UserController extends Controller\n";
echo "{\n";
echo "    public function __construct(\n";
echo "        private UserRepositoryInterface \$userRepository\n";
echo "    ) {}\n";
echo "\n";
echo "    public function index()\n";
echo "    {\n";
echo "        \$users = \$this->userRepository->all();\n";
echo "        return view('users.index', compact('users'));\n";
echo "    }\n";
echo "}\n\n";

echo "// Service Layer\n";
echo "class UserController extends Controller\n";
echo "{\n";
echo "    public function __construct(\n";
echo "        private UserService \$userService\n";
echo "    ) {}\n";
echo "\n";
echo "    public function store(Request \$request)\n";
echo "    {\n";
echo "        \$user = \$this->userService->create(\$request->validated());\n";
echo "        return redirect()->route('users.show', \$user);\n";
echo "    }\n";
echo "}\n\n";

echo "// CQRS\n";
echo "class UserController extends Controller\n";
echo "{\n";
echo "    public function store(CreateUserRequest \$request)\n";
echo "    {\n";
echo "        \$command = new CreateUserCommand(\n";
echo "            \$request->name,\n";
echo "            \$request->email,\n";
echo "            \$request->validated()\n";
echo "        );\n";
echo "        \n";
echo "        \$result = \$this->commandBus->dispatch(\$command);\n";
echo "        \n";
echo "        return redirect()->route('users.show', \$result);\n";
echo "    }\n";
echo "}\n\n";

echo "=== Benefits of Laravel Architex ===\n";
echo "✅ Save time setting up project structure\n";
echo "✅ Ensure consistent coding conventions and architecture\n";
echo "✅ Help new projects or large teams easily standardize structure\n";
echo "✅ Support for popular architecture patterns\n";
echo "✅ Flexible template engine for customization\n";
echo "✅ Auto registration in service providers\n";
echo "✅ Built-in integration with Laravel Artisan commands\n"; 