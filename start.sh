#!/bin/bash
# Startup script for Railway deployment

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Clear config cache (so Railway env vars are read fresh)
php artisan config:clear 2>/dev/null || true
php artisan config:cache 2>/dev/null || true

# Run migrations
php artisan migrate --force

# Start PHP built-in server (Railway will use PORT env var)
php artisan serve --host=0.0.0.0 --port=$PORT
