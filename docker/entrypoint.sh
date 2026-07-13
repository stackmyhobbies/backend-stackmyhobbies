#!/bin/sh
set -e

echo ">>> Entrypoint: verificando entorno..."

# Espera a la base de datos
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

echo ">>> Ajustando permisos de storage..."
chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# echo ">>> Ejecutando migraciones..."
# php artisan migrate --force

echo ">>> Ejecutando migraciones con refresh..."
php artisan migrate:refresh --seed --force

echo ">>> Limpiando caches..."
php artisan config:clear
php artisan cache:clear

echo ">>> Enlazando storage..."
php artisan storage:link --quiet 2>/dev/null || true

echo ">>> Optimizando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">>> Iniciando php-fpm en background..."
php-fpm &

echo ">>> Esperando que php-fpm esté listo en 127.0.0.1:9000..."
COUNT=0
until nc -z 127.0.0.1 9000 2>/dev/null; do
  COUNT=$((COUNT + 1))
  echo ">>> Intento $COUNT/15 esperando php-fpm..."
  if [ "$COUNT" -ge 15 ]; then
    echo ">>> ERROR: php-fpm no levantó. Abortando."
    exit 1
  fi
  sleep 1
done
echo ">>> php-fpm listo."

echo ">>> Iniciando Caddy en :${PORT:-8080}..."
exec caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
