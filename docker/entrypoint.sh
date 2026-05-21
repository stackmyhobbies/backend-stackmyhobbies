#!/bin/sh
set -e

echo ">>> Entrypoint: verificando entorno..."

if [ -n "$DB_HOST" ]; then
  echo ">>> Esperando a la base de datos en $DB_HOST:${DB_PORT:-5432}..."
  MAX_TRIES=30
  COUNT=0
  until php -r "
    try {
      new PDO(
        'pgsql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '5432') . ';dbname=' . getenv('DB_DATABASE') . ';sslmode=require',
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_TIMEOUT => 5]
      );
      exit(0);
    } catch (Exception \$e) {
      file_put_contents('php://stderr', \$e->getMessage() . PHP_EOL);
      exit(1);
    }
  " 2>/tmp/db_error.txt; do
    COUNT=$((COUNT + 1))
    echo ">>> Intento $COUNT/$MAX_TRIES — $(cat /tmp/db_error.txt)"
    if [ "$COUNT" -ge "$MAX_TRIES" ]; then
      echo ">>> ERROR: La base de datos no respondió. Abortando."
      exit 1
    fi
    sleep 2
  done
  echo ">>> Base de datos lista."
fi

echo ">>> Ejecutando migraciones..."
#php artisan migrate --force
php artisan migrate:fresh --force --seed
php artisan queue:work --daemon &

echo ">>> Optimizando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">>> Enlazando storage..."
php artisan storage:link --quiet 2>/dev/null || true

echo ">>> Iniciando Caddy en segundo plano..."
caddy run --config /etc/caddy/Caddyfile --adapter caddyfile &

echo ">>> Iniciando php-fpm..."
exec "$@"
