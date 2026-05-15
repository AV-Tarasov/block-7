#!/bin/bash

set -e

echo "🚀 Start deploy..."

APP_DIR=$(pwd)

echo "Go to project folder"
cd $APP_DIR

echo "Pull latest changes"
git pull origin main

echo "Rebuild containers"
docker compose up -d --build

echo "Wait containers..."
sleep 5

echo "Run migrations"
docker compose exec -T app php artisan migrate --force

echo "Clear cache"
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear

echo "Healthcheck"
curl -f http://localhost || exit 1

echo "Deploy successful"
