#!/bin/bash

if [ ! -f .env ]; then
    echo "error: .env file doesn't exist"
    exit 1
fi

echo "JWT_SECRET=$(openssl rand -base64 16)" >> .env