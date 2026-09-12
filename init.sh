#!/bin/bash
set -e

echo "🚀 [1/4] Initializing Environment Variables..."
if [ ! -f "tp8-admin/.env" ]; then
    cp tp8-admin/.env.example tp8-admin/.env
    echo "  ✅ Created tp8-admin/.env"
fi

if [ ! -f "hyperf-service/.env" ]; then
    cp hyperf-service/.env.example hyperf-service/.env
    echo "  ✅ Created hyperf-service/.env"
fi

echo "📦 [2/4] Installing ThinkPHP 8 Dependencies..."
cd tp8-admin
if command -v composer &> /dev/null; then
    composer install --no-interaction
    echo "  ✅ TP8 Composer dependencies installed"
fi
cd ..

echo "🚀 [3/4] Installing Hyperf Dependencies..."
cd hyperf-service
if command -v composer &> /dev/null; then
    composer install --no-interaction
    echo "  ✅ Hyperf Composer dependencies installed"
fi
cd ..

echo "⚛️ [4/4] Installing React Web Dependencies..."
cd react-web
if command -v npm &> /dev/null; then
    npm install
    echo "  ✅ NPM dependencies installed"
fi
cd ..

echo "🎉 Initialization complete! You are ready to develop or run with Docker Compose."
