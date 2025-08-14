#!/bin/bash

# Laravel Architex VS Code Extensions Installer
# This script installs recommended VS Code extensions for Laravel development

echo "🚀 Installing VS Code Extensions for Laravel Architex Development"
echo "================================================================"
echo ""

# Check if code command is available
if ! command -v code &> /dev/null; then
    echo "❌ VS Code 'code' command not found."
    echo "Please install VS Code and add it to your PATH."
    echo "You can install extensions manually from the VS Code marketplace."
    exit 1
fi

# Array of extension IDs
extensions=(
    "bmewburn.vscode-intelephense-client"
    "onecentlin.laravel-blade"
    "onecentlin.laravel5-snippets"
    "amiralizadeh9480.laravel-extra-intellisense"
    "ryannaddy.laravel-artisan"
    "ms-vscode.vscode-json"
    "bradlc.vscode-tailwindcss"
    "esbenp.prettier-vscode"
    "ms-vscode.vscode-php-debug"
    "neilbrayfield.php-docblocker"
    "junstyle.php-cs-fixer"
    "mehedidracula.php-namespace-resolver"
    "formulahendry.auto-rename-tag"
    "ms-vscode.vscode-typescript-next"
    "ms-vscode.vscode-php-pack"
    "xdebug.php-debug"
    "mikestead.dotenv"
    "editorconfig.editorconfig"
    "ms-vscode.vscode-git"
    "eamodio.gitlens"
    "ms-vscode.vscode-docker"
    "ms-azuretools.vscode-docker"
    "ms-vscode.vscode-yaml"
    "redhat.vscode-yaml"
    "ms-vscode.vscode-markdownlint"
    "davidanson.vscode-markdownlint"
    "streetsidesoftware.code-spell-checker"
    "ms-vscode.vscode-xml"
    "ms-vscode.vscode-css-peek"
    "ms-vscode.vscode-html-css-support"
    "ms-vscode.vscode-javascript-debug"
    "ms-vscode.vscode-js-debug"
    "ms-vscode.vscode-js-debug-companion"
)

# Function to install extension
install_extension() {
    local extension=$1
    echo "📦 Installing $extension..."
    if code --install-extension "$extension"; then
        echo "✅ Successfully installed $extension"
    else
        echo "❌ Failed to install $extension"
    fi
}

# Install each extension
for extension in "${extensions[@]}"; do
    install_extension "$extension"
done

echo ""
echo "🎉 Extension installation completed!"
echo ""
echo "📋 Installed Extensions:"
echo "========================"
for extension in "${extensions[@]}"; do
    echo "✅ $extension"
done

echo ""
echo "🔧 Next Steps:"
echo "1. Restart VS Code to ensure all extensions are loaded"
echo "2. Open this project in VS Code: code ."
echo "3. Check the Extensions panel to verify installations"
echo "4. Test Laravel Architex commands: php artisan make:repository User"
echo ""
echo "📚 Useful VS Code Commands:"
echo "- Cmd/Ctrl + Shift + P: Command Palette"
echo "- Cmd/Ctrl + Shift + X: Extensions"
echo "- Cmd/Ctrl + Shift + E: Explorer"
echo "- Cmd/Ctrl + Shift + G: Source Control"
echo "- Cmd/Ctrl + Shift + D: Debug"
echo ""
echo "🚀 Happy coding with Laravel Architex!" 