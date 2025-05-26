# API de FitCalendar
## Autenticación
Todos los endpoints protegidos requieren autenticación mediante token Bearer de Sanctum.
### Login
``` 
POST /api/login
```
**Parámetros de solicitud:**
- `email`: Correo electrónico del usuario
- `password`: Contraseña del usuario

**Respuesta:**
``` json
{
  "token": "token_de_autenticacion"
}
```
### Registro
``` 
POST /api/register
```
**Parámetros de solicitud:**
- `name`: Nombre completo
- `email`: Correo electrónico
- `password`: Contraseña
- : Confirmación de contraseña `password_confirmation`

### Cerrar sesión
``` 
POST /api/logout
```
_Requiere autenticación_

## Gestión de Perfil de Entrenador (Coach)

### Obtener perfil de entrenador
```
GET /api/coach/profile
``` 
_Requiere autenticación como entrenador_

**Respuesta:**
```json
{
  "id": 1,
  "user_id": 1,
  "description": "Entrenador profesional con 10 años de experiencia",
  "city": "Barcelona",
  "country": "España",
  "coach_type": "Personal Trainer",
  "verified": true,
  "organization_id": 1,
  "payment_info": "IBAN ES1234567890123456789012",
  "created_at": "2023-01-01T00:00:00.000000Z",
  "updated_at": "2023-01-01T00:00:00.000000Z",
  "sports": [
    {
      "id": 1,
      "name": "Yoga",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z",
      "pivot": {
        "coach_id": 1,
        "sport_id": 1,
        "specific_price": "35.00",
        "specific_location": "Centro Deportivo",
        "session_duration_minutes": 60
      }
    }
  ],
  "organization": {
    "id": 1,
    "name": "FitGym",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```
### Actualizar perfil de entrenador
``` 
PUT /api/coach/profile
```
_Requiere autenticación como entrenador_
**Parámetros de solicitud:**
- `description`: Descripción o biografía del entrenador (opcional)
- `city`: Ciudad donde opera el entrenador (opcional)
- `country`: País donde opera el entrenador (opcional)
- : Tipo de entrenador (opcional) `coach_type`
- : ID de la organización a la que pertenece el entrenador (opcional) `organization_id`
- : Información de pago del entrenador (opcional) `payment_info`

**Respuesta:**
``` json
{
  "message": "Perfil de entrenador actualizado correctamente",
  "coach": {
    "id": 1,
    "user_id": 1,
    "description": "Entrenador profesional con 15 años de experiencia",
    "city": "Madrid",
    "country": "España",
    "coach_type": "Personal Trainer",
    "verified": true,
    "organization_id": 1,
    "payment_info": "IBAN ES1234567890123456789012",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-02T00:00:00.000000Z"
  }
}
```
### Asignar deportes al entrenador
``` 
POST /api/coach/sports
```
_Requiere autenticación como entrenador_
**Parámetros de solicitud:**
- : Array de deportes (requerido)
    - `id`: ID del deporte (requerido)
    - : Precio específico para este deporte (opcional) `specific_price`
    - : Ubicación específica para este deporte (opcional) `specific_location`
    - : Duración de la sesión en minutos (opcional, mínimo 15) `session_duration_minutes`

`sports`

**Ejemplo de solicitud:**


## Gestión de Disponibilidad (AvailabilitySlot)
### Listar franjas de disponibilidad (para entrenadores)
``` 
GET /api/availability-slots
```
_Requiere autenticación como entrenador_
**Respuesta:**
``` json
[
  {
    "id": 1,
    "coach_id": 1,
    "sport_id": 1,
    "weekday": 1,
    "start_time": "10:00",
    "end_time": "11:00",
    "is_online": false,
    "location": "Gimnasio Central",
    "capacity": 1,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z",
    "sport": {
      "id": 1,
      "name": "Yoga",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  }
]
```
### Crear franja de disponibilidad
``` 
POST /api/availability-slots
```
_Requiere autenticación como entrenador_
**Parámetros de solicitud:**
- : ID del deporte (requerido) `sport_id`
- `weekday`: Día de la semana (0-6, donde 0 es domingo) (requerido)
- : Hora de inicio en formato HH:MM (requerido) `start_time`
- : Hora de fin en formato HH:MM (requerido, debe ser posterior a start_time) `end_time`
- : Booleano que indica si la sesión es online (requerido) `is_online`
- `location`: Ubicación de la sesión (opcional)
- `capacity`: Capacidad máxima (opcional, mínimo 1)

**Respuesta:**
``` json
{
  "message": "Franja de disponibilidad creada correctamente",
  "availabilitySlot": {
    "id": 1,
    "coach_id": 1,
    "sport_id": 1,
    "weekday": 1,
    "start_time": "10:00",
    "end_time": "11:00",
    "is_online": false,
    "location": "Gimnasio Central",
    "capacity": 1,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```
### Obtener detalles de una franja de disponibilidad
``` 
GET /api/availability-slots/{id}
```
_Requiere autenticación como entrenador_
**Respuesta:**
``` json
{
  "id": 1,
  "coach_id": 1,
  "sport_id": 1,
  "weekday": 1,
  "start_time": "10:00",
  "end_time": "11:00",
  "is_online": false,
  "location": "Gimnasio Central",
  "capacity": 1,
  "created_at": "2023-01-01T00:00:00.000000Z",
  "updated_at": "2023-01-01T00:00:00.000000Z",
  "sport": {
    "id": 1,
    "name": "Yoga",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```
### Actualizar franja de disponibilidad
``` 
PUT /api/availability-slots/{id}
```
_Requiere autenticación como entrenador_
**Parámetros de solicitud:**
- : ID del deporte (opcional) `sport_id`
- `weekday`: Día de la semana (0-6, donde 0 es domingo) (opcional)
- : Hora de inicio en formato HH:MM (opcional) `start_time`
- : Hora de fin en formato HH:MM (opcional, debe ser posterior a start_time) `end_time`
- : Booleano que indica si la sesión es online (opcional) `is_online`
- `location`: Ubicación de la sesión (opcional)
- `capacity`: Capacidad máxima (opcional, mínimo 1)

**Respuesta:**
``` json
{
  "message": "Franja de disponibilidad actualizada correctamente",
  "availabilitySlot": {
    "id": 1,
    "coach_id": 1,
    "sport_id": 1,
    "weekday": 1,
    "start_time": "10:00",
    "end_time": "11:00",
    "is_online": false,
    "location": "Gimnasio Central",
    "capacity": 1,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```
### Eliminar franja de disponibilidad
``` 
DELETE /api/availability-slots/{id}
```
_Requiere autenticación como entrenador_
**Respuesta:**
``` json
{
  "message": "Franja de disponibilidad eliminada correctamente"
}
```
### Listar franjas de disponibilidad de un entrenador específico (endpoint público)
``` 
GET /api/coaches/{coachId}/availability-slots
```
**Respuesta:**
``` json
[
  {
    "id": 1,
    "coach_id": 1,
    "sport_id": 1,
    "weekday": 1,
    "start_time": "10:00",
    "end_time": "11:00",
    "is_online": false,
    "location": "Gimnasio Central",
    "capacity": 1,
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z",
    "sport": {
      "id": 1,
      "name": "Yoga",
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-01T00:00:00.000000Z"
    }
  }
]
```
## Gestión de Reservas (Booking)
### Listar entrenadores disponibles (endpoint público)
``` 
GET /api/available-coaches
```
**Respuesta:**
``` json
[
  {
    "id": 1,
    "user_id": 1,
    "bio": "Entrenador profesional con 10 años de experiencia",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com"
    },
    "sports": [
      {
        "id": 1,
        "name": "Yoga",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
]
```
### Listar reservas del usuario
``` 
GET /api/bookings
```
_Requiere autenticación_
**Respuesta:**
``` json
[
  {
    "id": 1,
    "student_id": 2,
    "coach_id": 1,
    "availability_slot_id": 1,
    "type": "Individual",
    "session_at": "2023-12-04T10:00:00.000000Z",
    "session_duration_minutes": 60,
    "status": "Pendiente",
    "total_amount": "30.00",
    "currency": "EUR",
    "payment_status": "Pendiente",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z",
    "coach": {
      "id": 1,
      "user_id": 1,
      "user": {
        "id": 1,
        "name": "Juan Pérez"
      }
    },
    "student": {
      "id": 2,
      "name": "María López"
    },
    "availabilitySlot": {
      "id": 1,
      "coach_id": 1,
      "sport_id": 1,
      "weekday": 1,
      "start_time": "10:00",
      "end_time": "11:00"
    }
  }
]
```
### Crear una reserva
``` 
POST /api/bookings
```
_Requiere autenticación_
**Parámetros de solicitud:**
- : ID del entrenador (requerido) `coach_id`
- : ID del deporte (requerido) `sport_id`
- : Fecha y hora de la sesión en formato ISO 8601 (requerido, debe ser posterior a la fecha actual) `session_at`
- : ID de la franja de disponibilidad (requerido) `availability_slot_id`

**Respuesta:**
``` json
{
  "message": "Reserva creada correctamente",
  "booking": {
    "id": 1,
    "student_id": 2,
    "coach_id": 1,
    "availability_slot_id": 1,
    "type": "Individual",
    "session_at": "2023-12-04T10:00:00.000000Z",
    "session_duration_minutes": 60,
    "status": "Pendiente",
    "total_amount": "30.00",
    "currency": "EUR",
    "payment_status": "Pendiente",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
}
```
### Obtener detalles de una reserva
``` 
GET /api/bookings/{id}
```
_Requiere autenticación_
**Respuesta:**
``` json
{
  "id": 1,
  "student_id": 2,
  "coach_id": 1,
  "availability_slot_id": 1,
  "type": "Individual",
  "session_at": "2023-12-04T10:00:00.000000Z",
  "session_duration_minutes": 60,
  "status": "Pendiente",
  "total_amount": "30.00",
  "currency": "EUR",
  "payment_status": "Pendiente",
  "created_at": "2023-01-01T00:00:00.000000Z",
  "updated_at": "2023-01-01T00:00:00.000000Z",
  "coach": {
    "id": 1,
    "user_id": 1,
    "user": {
      "id": 1,
      "name": "Juan Pérez"
    }
  },
  "student": {
    "id": 2,
    "name": "María López"
  },
  "availabilitySlot": {
    "id": 1,
    "coach_id": 1,
    "sport_id": 1,
    "weekday": 1,
    "start_time": "10:00",
    "end_time": "11:00"
  }
}
```
### Cancelar una reserva
``` 
PATCH /api/bookings/{id}/cancel
```
_Requiere autenticación_
**Parámetros de solicitud:**
- : Motivo de cancelación (requerido) `cancelled_reason`

**Respuesta:**
``` json
{
  "message": "Reserva cancelada correctamente",
  "booking": {
    "id": 1,
    "student_id": 2,
    "coach_id": 1,
    "availability_slot_id": 1,
    "type": "Individual",
    "session_at": "2023-12-04T10:00:00.000000Z",
    "session_duration_minutes": 60,
    "status": "Cancelada",
    "total_amount": "30.00",
    "currency": "EUR",
    "payment_status": "Pendiente",
    "cancelled_at": "2023-01-02T00:00:00.000000Z",
    "cancelled_reason": "No podré asistir",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-02T00:00:00.000000Z"
  }
}
```
### Marcar una reserva como pagada (solo entrenadores)
``` 
PATCH /api/bookings/{id}/mark-as-paid
```
_Requiere autenticación como entrenador_
**Respuesta:**
``` json
{
  "message": "Pago marcado como completado",
  "booking": {
    "id": 1,
    "student_id": 2,
    "coach_id": 1,
    "availability_slot_id": 1,
    "type": "Individual",
    "session_at": "2023-12-04T10:00:00.000000Z",
    "session_duration_minutes": 60,
    "status": "Pendiente",
    "total_amount": "30.00",
    "currency": "EUR",
    "payment_status": "Completado",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-02T00:00:00.000000Z"
  }
}
```
## Notas para el desarrollo del PoC
En esta primera versión del PoC (Prueba de Concepto) se han simplificado varios aspectos:
1. Todas las reservas son de tipo "Individual" (no hay clases grupales).
2. No se calcula ninguna comisión de plataforma (platform_fee).
3. Duración de sesión fija de 60 minutos.
4. Precio fijo de 30€ por sesión.
5. No hay filtros complejos en los listados.

API con la funcionalidad básica para gestionar disponibilidad de entrenadores y reservas de sesiones, permitiendo un flujo completo desde la creación de franjas de disponibilidad hasta la confirmación del pago.
