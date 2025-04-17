#!/bin/bash

if [ ! -f .env ]; then
    echo "Error: .env file doesn't exist"
    echo "Please rename .env.example to .env and configure the variables"
    exit 1
fi

DC_ARGS="--env-file .env -f ./docker/compose.yml"

echo "Starting containers..."
docker-compose $DC_ARGS up -d --build
if [ $? -ne 0 ]; then
    echo "Error: Failed to start containers"
    exit 1
fi

echo "Installing PHP dependencies..."
docker-compose $DC_ARGS exec php bash -c "composer install --no-interaction --optimize-autoloader"
if [ $? -ne 0 ]; then
    echo "Error: Composer install failed"
    exit 1
fi

echo "Generating JWT keys..."
docker-compose $DC_ARGS exec php php bin/console lexik:jwt:generate-keypair --no-interaction
if [ $? -ne 0 ]; then
    echo "Warning: JWT key generation failed (might be okay if keys exist)"
fi

echo "Running database migrations..."
docker-compose $DC_ARGS exec php php bin/console doctrine:migrations:migrate --no-interaction
if [ $? -ne 0 ]; then
    echo "Error: Migrations failed"
    exit 1
fi

echo "Clearing cache..."
docker-compose $DC_ARGS exec php php bin/console cache:clear
if [ $? -ne 0 ]; then
    echo "Error: Cache clear failed"
    exit 1
fi

echo "Setting up permissions..."
docker-compose $DC_ARGS exec php chmod -R 777 var/
if [ $? -ne 0 ]; then
    echo "Warning: Permission setting failed"
fi

echo "Setup completed successfully"