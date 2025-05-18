
# API de FitCalendar - Documentación

Esta documentación detalla los endpoints disponibles en la API de FitCalendar, cómo autenticarse y qué datos puedes enviar y recibir.

## Autenticación

La API utiliza Laravel Sanctum para la autenticación basada en tokens.

### Obtener token de acceso

**Endpoint**: `POST /api/auth/login`

**Headers**:
```
Accept: application/json Content-Type: application/json
``` 

**Request Body**:
```
json { "email": "usuario@ejemplo.com", "password": "contraseña" }
``` 

**Respuesta exitosa** (200 OK):
```
json { "user": { "id": 1, "name": "Nombre Usuario", "email": "usuario@ejemplo.com", "role": "Student" }, "token": "1|laravel_sanctum_token_example..." }
``` 

**Respuesta de error** (401 Unauthorized):
```
json { "message": "Credenciales incorrectas" }
``` 

### Usar el token en solicitudes autenticadas

Para todas las solicitudes autenticadas, incluye el siguiente encabezado:
```
Authorization: Bearer tu_token_de_acceso
``` 

### Cerrar sesión (invalidar token)

**Endpoint**: `POST /api/auth/logout`

**Headers**:
```
Accept: application/json Authorization: Bearer tu_token_de_acceso
``` 

**Respuesta exitosa** (200 OK):
```
json { "message": "Sesión cerrada correctamente" }
``` 

## Endpoints de Usuarios

### Obtener perfil del usuario actual

**Endpoint**: `GET /api/user/profile`

**Headers**:
```
Accept: application/json Authorization: Bearer tu_token_de_acceso
``` 

**Respuesta** (200 OK):
```json
{
    "id": 1,
    "name": "Nombre Usuario",
    "email": "usuario@ejemplo.com",
    "role": "Student",
    "language": "es",
    "profile_picture_url": "https://ejemplo.com/avatar.jpg",
    "created_at": "2023-06-15T14:30:00.000000Z",
    "updated_at": "2023-06-15T14:30:00.000000Z"
}
```

### Actualizar perfil de usuario
**Endpoint**: `PUT /api/user/profile`
**Headers**:
``` 
Accept: application/json
Content-Type: application/json
Authorization: Bearer tu_token_de_acceso
```
**Request Body**:
``` json
{
    "name": "Nuevo Nombre",
    "language": "en",
    "profile_picture_url": "https://ejemplo.com/nueva-imagen.jpg"
}
```
**Respuesta** (200 OK):
``` json
{
    "message": "Perfil actualizado correctamente",
    "user": {
        "id": 1,
        "name": "Nuevo Nombre",
        "email": "usuario@ejemplo.com",
        "role": "Student",
        "language": "en",
        "profile_picture_url": "https://ejemplo.com/nueva-imagen.jpg",
        "created_at": "2023-06-15T14:30:00.000000Z",
        "updated_at": "2023-06-16T09:45:00.000000Z"
    }
}
```
## Endpoints de Entrenadores (Coaches)
### Listar entrenadores disponibles
**Endpoint**: `GET /api/coaches`
**Parámetros de consulta opcionales**:
- : Filtrar por deporte (ejemplo: `?sport_id=1`) `sport_id`
- `city`: Filtrar por ciudad (ejemplo: `?city=Madrid`)
- : Filtrar solo verificados (ejemplo: `?verified=true`) `verified`

**Headers**:
``` 
Accept: application/json
```
**Respuesta** (200 OK):
``` json
{
    "data": [
        {
            "id": 1,
            "user_id": 5,
            "name": "Juan Entrenador",
            "description": "Entrenador profesional de tenis con 10 años de experiencia",
            "city": "Madrid",
            "country": "España",
            "coach_type": "Individual",
            "verified": true,
            "sports": [
                {
                    "id": 1,
                    "name_es": "Tenis",
                    "name_en": "Tennis"
                }
            ],
            "profile_picture_url": "https://ejemplo.com/avatar.jpg"
        },
        {
            "id": 2,
            "user_id": 8,
            "name": "María Coach",
            "description": "Especialista en yoga y pilates",
            "city": "Barcelona",
            "country": "España",
            "coach_type": "Individual",
            "verified": true,
            "sports": [
                {
                    "id": 4,
                    "name_es": "Yoga",
                    "name_en": "Yoga"
                },
                {
                    "id": 5,
                    "name_es": "Pilates",
                    "name_en": "Pilates"
                }
            ],
            "profile_picture_url": "https://ejemplo.com/avatar2.jpg"
        }
    ],
    "links": {
        "first": "http://localhost/api/coaches?page=1",
        "last": "http://localhost/api/coaches?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost/api/coaches",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```
### Ver detalle de un entrenador
**Endpoint**: `GET /api/coaches/{id}`
**Headers**:
``` 
Accept: application/json
```
**Respuesta** (200 OK):
``` json
{
    "id": 1,
    "user_id": 5,
    "name": "Juan Entrenador",
    "description": "Entrenador profesional de tenis con 10 años de experiencia",
    "city": "Madrid",
    "country": "España",
    "coach_type": "Individual",
    "verified": true,
    "sports": [
        {
            "id": 1,
            "name_es": "Tenis",
            "name_en": "Tennis"
        }
    ],
    "profile_picture_url": "https://ejemplo.com/avatar.jpg",
    "availability": [
        {
            "day": "monday",
            "start_time": "09:00:00",
            "end_time": "18:00:00"
        },
        {
            "day": "wednesday",
            "start_time": "09:00:00",
            "end_time": "18:00:00"
        },
        {
            "day": "friday",
            "start_time": "09:00:00",
            "end_time": "15:00:00"
        }
    ]
}
```
## Endpoints de Deportes
### Listar deportes disponibles
**Endpoint**: `GET /api/sports`
**Headers**:
``` 
Accept: application/json
```
**Respuesta** (200 OK):
``` json
{
    "data": [
        {
            "id": 1,
            "name_es": "Tenis",
            "name_en": "Tennis",
            "icon": "tennis"
        },
        {
            "id": 2,
            "name_es": "Fútbol",
            "name_en": "Soccer",
            "icon": "soccer"
        },
        {
            "id": 3,
            "name_es": "Natación",
            "name_en": "Swimming",
            "icon": "swimming"
        }
    ]
}
```
## Endpoints de Reservas (Requieren autenticación)
### Listar reservas del usuario
**Endpoint**: `GET /api/bookings`
**Headers**:
``` 
Accept: application/json
Authorization: Bearer tu_token_de_acceso
```
**Respuesta** (200 OK):
``` json
{
    "data": [
        {
            "id": 1,
            "coach_id": 1,
            "coach_name": "Juan Entrenador",
            "sport_id": 1,
            "sport_name": "Tenis",
            "booking_date": "2023-07-10",
            "start_time": "10:00:00",
            "end_time": "11:00:00",
            "status": "confirmed",
            "notes": "Traer raqueta propia",
            "price": 45.00,
            "created_at": "2023-07-01T14:30:00.000000Z"
        },
        {
            "id": 2,
            "coach_id": 2,
            "coach_name": "María Coach",
            "sport_id": 4,
            "sport_name": "Yoga",
            "booking_date": "2023-07-15",
            "start_time": "17:00:00",
            "end_time": "18:00:00",
            "status": "pending",
            "notes": "Clase para principiantes",
            "price": 30.00,
            "created_at": "2023-07-05T09:15:00.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/bookings?page=1",
        "last": "http://localhost/api/bookings?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost/api/bookings",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```
### Crear nueva reserva
**Endpoint**: `POST /api/bookings`
**Headers**:
``` 
Accept: application/json
Content-Type: application/json
Authorization: Bearer tu_token_de_acceso
```
**Request Body**:
``` json
{
    "coach_id": 1,
    "sport_id": 1,
    "booking_date": "2023-07-20",
    "start_time": "16:00:00",
    "end_time": "17:00:00",
    "notes": "Primera clase, nivel básico"
}
```
**Respuesta exitosa** (201 Created):
``` json
{
    "message": "Reserva creada correctamente",
    "booking": {
        "id": 3,
        "coach_id": 1,
        "coach_name": "Juan Entrenador",
        "sport_id": 1,
        "sport_name": "Tenis",
        "booking_date": "2023-07-20",
        "start_time": "16:00:00",
        "end_time": "17:00:00",
        "status": "pending",
        "notes": "Primera clase, nivel básico",
        "price": 45.00,
        "created_at": "2023-07-08T11:30:00.000000Z"
    }
}
```
### Cancelar reserva
**Endpoint**: `PUT /api/bookings/{id}/cancel`
**Headers**:
``` 
Accept: application/json
Authorization: Bearer tu_token_de_acceso
```
**Respuesta exitosa** (200 OK):
``` json
{
    "message": "Reserva cancelada correctamente",
    "booking": {
        "id": 3,
        "status": "cancelled",
        "cancellation_reason": "cancelled_by_student",
        "cancelled_at": "2023-07-09T14:25:00.000000Z"
    }
}
```
## Códigos de estado HTTP
La API utiliza los siguientes códigos de estado HTTP:
- `200 OK`: La solicitud fue exitosa
- `201 Created`: El recurso fue creado exitosamente
- `400 Bad Request`: La solicitud contiene datos inválidos
- `401 Unauthorized`: Autenticación requerida o fallida
- `403 Forbidden`: El usuario no tiene permisos para acceder al recurso
- `404 Not Found`: El recurso solicitado no existe
- `422 Unprocessable Entity`: Los datos de la solicitud no pasaron la validación
- `500 Internal Server Error`: Error del servidor

## Manejo de errores
Todas las respuestas de error siguen el mismo formato:
``` json
{
    "message": "Mensaje descriptivo del error",
    "errors": {
        "campo1": [
            "Error específico para campo1"
        ],
        "campo2": [
            "Error específico para campo2"
        ]
    }
}
```
El campo `errors` solo aparece cuando hay errores de validación (código 422).
``` 

