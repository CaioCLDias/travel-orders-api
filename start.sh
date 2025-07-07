#!/bin/bash

echo "🔧 Setting permissions for storage and cache directories..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache


echo "📦 Installing PHP dependencies with Composer..."
composer install --no-dev --optimize-autoloader

echo "🧼 Clearing and caching Laravel configuration..."
cp .env.example .env
php artisan key:generate
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🛠️ Running database migrations and seeders..."
php artisan migrate --force
php artisan db:seed --force


echo "🧹 Starting queue background commnad..."
php artisan queue:work > storage/logs/queue.log 2>&1 &

echo "✅ Startup complete. Launching PHP..."
php -S 0.0.0.0:9000 -t public
        