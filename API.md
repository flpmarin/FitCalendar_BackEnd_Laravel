# 📘 Documentación de la API de FitCalendar

## 🛡️ Autenticación

Todos los endpoints protegidos requieren autenticación mediante token Bearer (Sanctum).

### 🔐 Login

```
POST /api/login
```

**Parámetros de solicitud:**

* `email`: Correo electrónico del usuario
* `password`: Contraseña del usuario

**Respuesta:**

```json
{
  "token": "token_de_autenticacion"
}
```

### 📝 Registro de usuario

```
POST /api/register
```
- **Método**: POST
- **Descripción**: Registrar un nuevo usuario en el sistema
- **Parámetros**:
    - `name`: Nombre del usuario (obligatorio)
    - `email`: Correo electrónico (obligatorio, único)
    - `password`: Contraseña (obligatorio)
    - `password_confirmation`: Confirmación de contraseña (obligatorio)
    - `role`: Rol del usuario (obligatorio, valores: "Student", "Coach", "Admin")
    - `language`: Idioma preferido (opcional, predeterminado: "es")
    - `age`: Edad del usuario (opcional)
    - `description`: Descripción personal (opcional)
- **Respuesta exitosa**: Token de autenticación y datos del usuario

### 🚪 Cerrar sesión

```
POST /api/logout
```

*Requiere autenticación*

---


## Perfil de Usuario

### Obtener Perfil de Usuario
- **URL**: `/api/user/profile`
- **Método**: GET
- **Descripción**: Obtener información del perfil del usuario autenticado
- **Autenticación**: Bearer Token
- **Respuesta exitosa**: Datos del perfil de usuario incluyendo edad y descripción

### Actualizar Perfil de Usuario
- **URL**: `/api/user/profile`
- **Método**: PUT
- **Descripción**: Actualizar datos del perfil de usuario
- **Autenticación**: Bearer Token
- **Parámetros**:
    - `age`: Edad del usuario (opcional, entero entre 10 y 120)
    - `description`: Descripción personal (opcional, máximo 1000 caracteres)
- **Respuesta exitosa**: Mensaje de confirmación y datos actualizados

## Perfil de Entrenador

### Obtener Perfil de Entrenador
- **URL**: `/api/coach/profile`
- **Método**: GET
- **Descripción**: Obtener información del perfil de entrenador
- **Autenticación**: Bearer Token (debe ser un usuario con rol "Coach")
- **Respuesta exitosa**: Datos del perfil de entrenador con relaciones

### Actualizar Perfil de Entrenador
- **URL**: `/api/coach/profile`
- **Método**: PUT
- **Descripción**: Actualizar datos del perfil de entrenador
- **Autenticación**: Bearer Token (debe ser un usuario con rol "Coach")
- **Parámetros**:
    - `description`: Descripción profesional (opcional)
    - `city`: Ciudad (opcional)
    - `country`: País (opcional)
    - `coach_type`: Tipo de entrenador (opcional, "Individual" o "Club")
    - `organization_id`: ID de la organización (opcional)
    - `payment_info`: Información de pago (opcional)
- **Respuesta exitosa**: Mensaje de confirmación y datos actualizados

### Asignar deportes

```
POST /api/coach/sports
```

*Requiere autenticación como entrenador*

**Parámetros de solicitud:**

* `sports`: Array de objetos con:

    * `id`: ID del deporte (requerido)
    * `specific_price`: Precio específico (opcional)
    * `specific_location`: Ubicación específica (opcional)
    * `session_duration_minutes`: Duración de sesión (opcional)

**Ejemplo:**

```json
{
  "sports": [
    {
      "id": 1,
      "specific_price": 35.00,
      "specific_location": "Centro Deportivo",
      "session_duration_minutes": 60
    }
  ]
}
```

---

## 📅 Disponibilidad

### Listar franjas de disponibilidad

```
GET /api/availability-slots
```

*Requiere autenticación como entrenador*

### Crear franja de disponibilidad

```
POST /api/availability-slots
```

**Parámetros:**

* `sport_id`: ID del deporte (requerido)
* `weekday`: Día (0=domingo a 6=sábado) (requerido)
* `start_time`: HH\:MM (requerido)
* `end_time`: HH\:MM (requerido)
* `is_online`: true/false (requerido)
* `location`: Ubicación (opcional)
* `capacity`: Capacidad (opcional)

### Ver franja de disponibilidad

```
GET /api/availability-slots/{id}
```

*Requiere autenticación como entrenador*

### Actualizar franja de disponibilidad

```
PUT /api/availability-slots/{id}
```

*Requiere autenticación*

**Parámetros opcionales:** mismos que en creación.

### Eliminar franja de disponibilidad

```
DELETE /api/availability-slots/{id}
```

*Requiere autenticación*

### Ver franjas de disponibilidad públicas

```
GET /api/coaches/{coachId}/availability-slots
```

---

## 📆 Reservas

### Listar entrenadores disponibles

```
GET /api/available-coaches
```

*Endpoint público*

### Listar reservas del usuario

```
GET /api/bookings
```

*Requiere autenticación*

### Crear una reserva

```
POST /api/bookings
```

**Parámetros:**

* `coach_id`: ID del entrenador (requerido)
* `sport_id`: ID del deporte (requerido)
* `availability_slot_id`: ID de la franja (requerido)
* `session_at`: Fecha y hora ISO 8601 (requerido)
* `type`: "Individual" o "Group" (opcional)

### Obtener reserva

```
GET /api/bookings/{id}
```

*Requiere autenticación*

### Cancelar reserva

```
PATCH /api/bookings/{id}/cancel
```

**Parámetros:**

* `cancelled_reason`: Motivo de la cancelación (requerido)

### Marcar como pagada

```
PATCH /api/bookings/{id}/mark-as-paid
```

*Requiere autenticación como entrenador*

---

## 🔧 Health Check

```
GET /api/health
```

*Verifica el estado de la API.*

---

## 🧪 Notas para PoC

* Todas las reservas son individuales por defecto.
* Precio fijo de 30€.
* Duración de sesión fija de 60 min.
* Sin comisiones ni filtros avanzados.

API enfocada en disponibilidad y reservas para entrenadores personales.
