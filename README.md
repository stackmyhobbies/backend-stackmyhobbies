# StackMyHobbies - Backend API

API REST para la gestión de hobbies (content items), usuarios, tipos de contenido, tags y estados de progreso. Construida con Laravel 12 y autenticación vía Sanctum.

## Requisitos

- PHP 8.2+
- [Composer](https://getcomposer.org/)
- [Node.js + npm](https://nodejs.org/)
- PostgreSQL (opcional, también funciona con SQLite para desarrollo)
- Redis (opcional, requerido para colas y Horizon)

## Instalación

```bash
# 1. Clonar el repositorio
git clone <repo-url> && cd backend-stackmyhobbies

# 2. Instalar dependencias PHP
composer install

# 3. Copiar el archivo de entorno
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Configurar la base de datos en .env
#    Por defecto usa SQLite (sin configuración adicional).
#    Si usas PostgreSQL, edita las variables DB_* en el .env

# 6. Ejecutar migraciones y seeders
php artisan migrate --seed

# 7. Crear el enlace simbólico de storage
php artisan storage:link

# 8. Instalar dependencias de frontend (Vite)
npm install
```

## Iniciar la aplicación

### Todo en uno (recomendado para desarrollo)

```bash
composer run dev
```

Esto levanta simultáneamente: servidor PHP, worker de colas, logs (Pail) y Vite.

### Servicios individuales

```bash
# Solo el servidor HTTP de desarrollo
php artisan serve

# Worker de colas
php artisan queue:listen --tries=1

# Vite (hot reload del frontend)
npm run dev
```

### Docker (producción)

```bash
docker build -t stackmyhobbies-api .
docker run -p 8080:8080 \
  -e DB_HOST=<host> \
  -e DB_DATABASE=stackmyhobbies-db \
  -e DB_USERNAME=<user> \
  -e DB_PASSWORD=<pass> \
  -e REDIS_HOST=<redis_host> \
  stackmyhobbies-api
```

## Variables de entorno principales

| Variable              | Descripción                          | Default              |
|-----------------------|--------------------------------------|----------------------|
| `DB_CONNECTION`       | Motor de base de datos               | `sqlite`             |
| `DB_DATABASE`         | Nombre de la base de datos           | `stackmyhobbies-db`  |
| `REDIS_HOST`          | Host de Redis                        | `127.0.0.1`          |
| `APP_FRONTEND_URL`    | URL del frontend (para CORS)         | `http://localhost:5173` |
| `CLOUDINARY_*`        | Credenciales de Cloudinary (media)   | —                    |

## Pruebas

```bash
composer test
# o
php artisan test --compact

# Filtrar por nombre de prueba
php artisan test --compact --filter=TestName
```

## Formateo de código

```bash
vendor/bin/pint --format agent
```

## Endpoints principales

| Método | Ruta                       | Auth       | Descripción        |
|--------|----------------------------|------------|--------------------|
| POST   | `/api/auth/sign-in`        | No         | Iniciar sesión     |
| POST   | `/api/auth/sign-up`        | No         | Registro           |
| POST   | `/api/auth/forgot-password`| No         | Recuperar contraseña |
| GET    | `/api/content-items`       | Sanctum    | Listar hobbies     |
| POST   | `/api/content-items`       | Sanctum    | Crear hobby        |
| GET    | `/api/admin/users`         | Sanctum+admin | Admin: listar usuarios |
```
