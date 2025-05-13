# FitCalendar - Guía de Instalación
## Requisitos Previos
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y funcionando en tu sistema
- [Git](https://git-scm.com/downloads) instalado

## Pasos para la Instalación
### 1. Clonar el Repositorio
``` bash
git clone [URL_DEL_REPOSITORIO]
cd fitcalendar
```
### 2. Configurar el Archivo de Entorno
Copia el archivo a un nuevo archivo llamado : `.env.example``.env`
``` bash
# En Windows
copy .env.example .env

# En macOS/Linux
cp .env.example .env
```
### 3. Configuración del Entorno
El archivo ya está preconfigurado para trabajar con Docker. Los valores importantes que debes revisar son: `.env`
- : Se generará automáticamente en un paso posterior `APP_KEY`
- : Configurado para PostgreSQL `DB_CONNECTION`
- : Establecido como (nombre del servicio de Docker) `DB_HOST``pgsql`
- : 5432 `DB_PORT`
- : laravel `DB_DATABASE`
- : sail `DB_USERNAME`
- : password `DB_PASSWORD`

### 4. Iniciar los Contenedores Docker
Laravel Sail es compatible tanto con Windows como con macOS/Linux, pero en Windows se debe ejecutar a través de WSL (Windows Subsystem for Linux).
#### Opción 1: Usando Docker Compose (recomendado para principiantes)
``` bash
docker-compose up -d
```
#### Opción 2: Usando Laravel Sail (si tienes WSL en Windows o usas macOS/Linux)
Si aún no tienes las dependencias instaladas:
``` bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    composer install --ignore-platform-reqs
```
Luego:
``` bash
./vendor/bin/sail up -d
```
Este comando iniciará todos los servicios definidos en el : `docker-compose.yml`
- Aplicación Laravel (PHP 8.4)
- PostgreSQL 15
- Mailpit (para pruebas de correo)
- Selenium (para pruebas de navegador)

### 5. Generar Clave de Aplicación
``` bash
# Con Docker Compose
docker-compose exec laravel.test php artisan key:generate

# O con Sail (si lo tienes configurado)
./vendor/bin/sail artisan key:generate
```
### 6. Ejecutar Migraciones y Semillas
``` bash
# Con Docker Compose
docker-compose exec laravel.test php artisan migrate --seed

# O con Sail (si lo tienes configurado)
./vendor/bin/sail artisan migrate --seed
```
### 7. Instalar Dependencias de Composer
Este paso puede omitirse si ya instalaste las dependencias al principio.
``` bash
# Con Docker Compose
docker-compose exec laravel.test composer install

# O con Sail (si lo tienes configurado)
./vendor/bin/sail composer install
```
### 8. Acceder a la Aplicación
Una vez completados los pasos anteriores, puedes acceder a la aplicación en tu navegador:
- **URL de la Aplicación**: [http://localhost](http://localhost)
- **Dashboard de Mailpit**: [http://localhost:8025](http://localhost:8025)

## Comandos Útiles
### Detener los Contenedores
``` bash
# Con Docker Compose
docker-compose down

# O con Sail (si lo tienes configurado)
./vendor/bin/sail down
```
### Reiniciar los Contenedores
``` bash
# Con Docker Compose
docker-compose restart

# O con Sail (si lo tienes configurado)
./vendor/bin/sail restart
```
### Ejecutar Pruebas
``` bash
# Con Docker Compose
docker-compose exec laravel.test php artisan test

# O con Sail (si lo tienes configurado)
./vendor/bin/sail test
```
### Acceder a la Base de Datos PostgreSQL
``` bash
# Con Docker Compose
docker-compose exec pgsql psql -U sail -d laravel

# O con Sail (si lo tienes configurado)
./vendor/bin/sail psql
```
## Solución de Problemas Comunes
### Puertos en Uso
Si recibes un error indicando que los puertos ya están en uso, puedes cambiar los puertos en el archivo : `.env`
- Cambia para la aplicación web (predeterminado: 80) `APP_PORT`
- Cambia para PostgreSQL (predeterminado: 5433) `FORWARD_DB_PORT`

### Problemas de Permisos en Windows
Si encuentras problemas de permisos en Windows, asegúrate de que Docker Desktop tenga acceso a la ubicación donde has clonado el repositorio y que WSL esté configurado correctamente si estás usando Sail.
### Datos de Acceso a Postgres desde Herramientas Externas
- **Host**: localhost
- **Puerto**: 5433 (o el valor de FORWARD_DB_PORT en tu .env)
- **Base de datos**: laravel
- **Usuario**: sail
- **Contraseña**: password

## Nota para Desarrolladores
La aplicación está configurada para utilizar PostgreSQL como base de datos principal. Si necesitas realizar cambios en la estructura de la base de datos, recuerda crear las migraciones correspondientes utilizando:
``` bash
# Con Docker Compose
docker-compose exec laravel.test php artisan make:migration nombre_de_la_migracion

# O con Sail (si lo tienes configurado)
./vendor/bin/sail artisan make:migration nombre_de_la_migracion
```

# FitCalendar
## Descripción del Proyecto
FitCalendar es una plataforma  que conecta entrenadores deportivos con estudiantes en busca de formación deportiva personalizada. El sistema permite la gestión completa del ciclo de reservas de clases deportivas, tanto individuales como grupales, y facilita la coordinación entre entrenadores y alumnos.

## Arquitectura del Sistema
El sistema está construido sobre una arquitectura moderna de Laravel 12, utilizando:
- **PHP 8.2+**: Aprovechando las características más recientes del lenguaje.
- **PostgreSQL**: Para almacenamiento robusto y consultas complejas.
- **Docker**: Contenedores para desarrollo y despliegue consistentes.
- **Laravel Sanctum**: Autenticación segura basada en tokens.
- **Eloquent ORM**: Modelado de datos y relaciones.
- **Migraciones**: Control de versiones de la estructura de la base de datos.
- **Seeds y Factories**: Datos de prueba para facilitar el desarrollo.

## Modelo de Datos
El sistema está construido alrededor de varias entidades principales:
- **Usuarios**: Sistema central de autenticación con roles (Estudiante, Entrenador, Administrador).
- **Entrenadores**: Perfiles especializados para los usuarios con rol de entrenador.
- **Deportes**: Catálogo de actividades deportivas disponibles en la plataforma.
- **Disponibilidad**: Slots regulares y específicos que definen cuándo un entrenador está disponible.
- **Clases**: Sesiones grupales programadas por los entrenadores.
- **Reservas**: Solicitudes de estudiantes para sesiones individuales o grupales.
- **Pagos**: Registro de transacciones económicas asociadas a las reservas.
- **Reseñas**: Evaluaciones de los estudiantes sobre sus experiencias con los entrenadores.

## Estado del Proyecto
FitCalendar se encuentra actualmente en fase de desarrollo. Esta versión incluye la estructura base de la API y el sistema de gestión de datos, preparado para la integración con un frontend basado en el diseño aprobado.
