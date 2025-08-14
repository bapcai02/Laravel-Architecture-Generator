#!/bin/bash

# Laravel Architex Missing Files Fixer
# This script creates missing files that Laravel expects to exist

echo "🔧 Fixing missing Laravel files..."
echo "=================================="
echo ""

# Function to create file if it doesn't exist
create_file_if_missing() {
    local file_path=$1
    local content=$2
    
    if [ ! -f "$file_path" ]; then
        echo "📝 Creating missing file: $file_path"
        mkdir -p "$(dirname "$file_path")"
        echo "$content" > "$file_path"
        echo "✅ Created: $file_path"
    else
        echo "✅ File exists: $file_path"
    fi
}

# EventServiceProvider
EVENT_PROVIDER_CONTENT='<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}'

# RepositoryServiceProvider
REPO_PROVIDER_CONTENT='<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository bindings will be added here when repositories are created
    }

    public function boot(): void
    {
        //
    }
}'

# UserController
USER_CONTROLLER_CONTENT='<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(["message" => "User controller working"]);
    }
}'

# Create missing files
create_file_if_missing "app/Providers/EventServiceProvider.php" "$EVENT_PROVIDER_CONTENT"
create_file_if_missing "app/Providers/RepositoryServiceProvider.php" "$REPO_PROVIDER_CONTENT"
create_file_if_missing "app/Http/Controllers/UserController.php" "$USER_CONTROLLER_CONTENT"

echo ""
echo "🎉 Missing files fixed!"
echo ""
echo "Now you can run:"
echo "php artisan make:repository User"
echo "php artisan make:service User"
echo "php artisan make:ddd UserManagement"
echo "php artisan make:cqrs CreateUser"
echo "php artisan make:event UserCreated"
echo ""
echo "🚀 Laravel Architex is ready to use!" 