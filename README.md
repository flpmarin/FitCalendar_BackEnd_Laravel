# FitCalendar - Guía de Instalación
## Requisitos Previos
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y funcionando
- [Git](https://git-scm.com/downloads) instalado

## Instrucciones de Instalación (Válido para Windows, macOS y Linux)
### 1. Clonar el Repositorio
``` bash
git clone [URL_DEL_REPOSITORIO]
cd fitcalendar
```
### 2. Configurar Archivo de Entorno
``` bash
# En Windows
copy .env.example .env

# En macOS/Linux
cp .env.example .env
```
### 3. Iniciar Docker
``` bash
docker-compose up -d
```
Este comando iniciará todos los contenedores necesarios para el proyecto.
### 4. Configurar la Aplicación
``` bash
# Generar clave de aplicación
docker-compose exec laravel.test php artisan key:generate

# Ejecutar migraciones y semillas
docker-compose exec laravel.test php artisan migrate --seed
```
### 5. Acceder a la Aplicación
- **URL de la Aplicación**: [http://localhost](http://localhost)
- **Dashboard de Mailpit**: [http://localhost:8025](http://localhost:8025)

## Comandos Básicos
### Detener Contenedores
``` bash
docker-compose down
```
### Reiniciar Contenedores
``` bash
docker-compose restart
```
### Ejecutar Comandos de Artisan
``` bash
docker-compose exec laravel.test php artisan [comando]
```
### Acceder a PostgreSQL
``` bash
docker-compose exec pgsql psql -U sail -d laravel
```
## Solución de Problemas
### Puertos en Uso
Si los puertos ya están en uso, puedes cambiarlos en el archivo : `.env`
- Para la aplicación web: (predeterminado: 80) `APP_PORT`
- Para PostgreSQL: (predeterminado: 5433) `FORWARD_DB_PORT`

### Acceso a Postgres desde Herramientas Externas
- **Host**: localhost
- **Puerto**: 5433 (o el valor de FORWARD_DB_PORT en tu .env)
- **Base de datos**: laravel
- **Usuario**: sail
- **Contraseña**: password

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
