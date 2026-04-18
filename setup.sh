#!/bin/bash

# SurveyLite Setup Script
# This script automates the installation of the Slim Framework survey system

echo "========================================="
echo "  SurveyLite - Slim Framework Setup"
echo "========================================="
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null
then
    echo "❌ Composer is not installed!"
    echo "Please install Composer first: https://getcomposer.org/download/"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo "✓ PHP version: $PHP_VERSION"

if php -r 'exit(version_compare(PHP_VERSION, "8.0.0", "<") ? 0 : 1);' 2>/dev/null
then
    echo "❌ PHP 8.0 or higher is required!"
    exit 1
fi

# Install Composer dependencies
echo ""
echo "📦 Installing Composer dependencies..."
composer install

if [ $? -ne 0 ]; then
    echo "❌ Composer install failed!"
    exit 1
fi

echo "✓ Dependencies installed successfully"

# Create storage directory
echo ""
echo "📁 Creating storage directory..."
mkdir -p storage
chmod 777 storage
echo "✓ Storage directory created"

# Create .htaccess if using Apache
if [ -f "public/.htaccess" ]; then
    echo "✓ Apache .htaccess already exists"
else
    echo "⚠️  No .htaccess found - create one if using Apache"
fi

echo ""
echo "========================================="
echo "  ✓ Installation Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo ""
echo "1. Start PHP development server:"
echo "   cd public && php -S localhost:8000"
echo ""
echo "2. Open browser: http://localhost:8000"
echo ""
echo "3. Admin login:"
echo "   URL: http://localhost:8000/admin/login"
echo "   Username: admin"
echo "   Password: admin123"
echo ""
echo "4. Upload sample.csv to test the system"
echo ""
echo "For Apache deployment, point DocumentRoot to public/ directory"
echo "========================================="
