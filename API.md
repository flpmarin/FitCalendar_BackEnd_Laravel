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

**Parámetros de solicitud:**

* `name`: Nombre completo (requerido)
* `email`: Correo electrónico (requerido)
* `password`: Contraseña (requerido)
* `password_confirmation`: Confirmación de contraseña (requerido)
* `role`: "Student" o "Coach" (requerido)
* `language`: Código de idioma (por ejemplo, "es", "en") (opcional ->por defecto 'es')

### 🚪 Cerrar sesión

```
POST /api/logout
```

*Requiere autenticación*

---

## 👤 Perfil de Entrenador

### Obtener perfil

```
GET /api/coach/profile
```

*Requiere autenticación como entrenador*

**Respuesta:** JSON con datos del entrenador, organización y deportes asignados.

### Actualizar perfil

```
PUT /api/coach/profile
```

*Requiere autenticación como entrenador*

**Parámetros de solicitud:**

* `description`: Biografía del entrenador (opcional)
* `city`: Ciudad (opcional)
* `country`: País (opcional)
* `coach_type`: Tipo de entrenador (opcional)
* `organization_id`: ID de organización (opcional)
* `payment_info`: Información de pago (opcional)

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
