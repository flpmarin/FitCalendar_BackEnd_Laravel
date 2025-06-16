# FitCalendar API Documentation
```markdown
---

#  Documentación de la API de FitCalendar

## Autenticación

Todos los endpoints protegidos requieren autenticación mediante token Bearer (Laravel Sanctum).

###  Login

```
POST /api/login
```

**Parámetros:**

- `email` (requerido)
- `password` (requerido)

```json
{
  "email": "usuario@example.com",
  "password": "password"
}
```

**Respuesta:**

```json
{
  "token": "token_de_autenticacion"
}
```

### Registro

```
POST /api/register
```

**Parámetros:**

- `name` (requerido)
- `email` (requerido)
- `password` (requerido)
- `password_confirmation` (requerido)
- `role` ("Student", "Coach", "Admin") (requerido)
- `language` (opcional, por defecto "es") — Idioma preferido del usuario.
- `age` (opcional) — Edad del usuario.
- `description` (opcional) — Descripción breve del usuario.

```json
{
  "name": "Nombre",
  "email": "usuario@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "Coach",
  "language": "es",
  "age": 30,
  "description": "Entrenador personal"
}
```

**Respuesta:**

```json
{
  "token": "...",
  "user":  "..."
}
```

### Logout

```
POST /api/logout
```

> Requiere token de autenticación

---

## Perfil de Usuario

### Obtener perfil

```
GET /api/user/profile
```

### Actualizar perfil

```
PUT /api/user/profile
```

**Parámetros opcionales:**

- `age` — Edad del usuario.
- `description` — Descripción breve del usuario.

```json
{
  "age": 30,
  "description": "Me gusta el deporte"
}
```

---

## Perfil de Entrenador

### Ver perfil del entrenador autenticado

```
GET /api/coach/profile
```

### Actualizar perfil

```
PUT /api/coach/profile
```

**Parámetros (todos opcionales):**

- `description` — Descripción del coach.
- `city` — Ciudad de residencia.
- `country` — País.
- `coach_type` — Tipo de entrenador.
- `organization_id` — ID de la organización (si aplica).
- `payment_info` — Información de pago.

```json
{
  "description": "Entrenador de natación",
  "city": "Madrid",
  "country": "España",
  "coach_type": "Personal",
  "organization_id": 2,
  "payment_info": "IBAN ES00 0000 0000 0000"
}
```

### Asignar deportes al entrenador

```
POST /api/coach/sports
```

**Parámetros:**

- `sports` (array de objetos, requerido)
    - `id` (requerido) — ID del deporte
    - `specific_price` (opcional) — Precio específico para este deporte
    - `specific_location` (opcional) — Ubicación específica
    - `session_duration_minutes` (opcional) — Duración de la sesión en minutos

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

## Disponibilidad puntual

> Actualmente la disponibilidad recurrente está desactivada.

### Ver todas las disponibilidades puntuales (propias o públicas)

```
GET /api/specific-availabilities
```

**Parámetros de consulta opcionales:**

- `coach_id` — Filtra por entrenador.
- `sport_id` — Filtra por deporte.
- `date` — Filtra por fecha (formato `YYYY-MM-DD`).
- `is_online` — Filtra por modalidad online (`true`/`false`).

### Crear nueva disponibilidad puntual

```
POST /api/specific-availabilities
```

**Parámetros:**

- `sport_id` (requerido)
- `date` (requerido, formato `YYYY-MM-DD`)
- `start_time` (requerido, formato `HH:mm`)
- `end_time` (requerido, formato `HH:mm`)
- `is_online` (requerido, booleano)
- `location` (opcional) — Lugar físico de la sesión.
- `capacity` (opcional, por defecto 1) — Número máximo de participantes.

```json
{
  "sport_id": 1,
  "date": "2025-07-01",
  "start_time": "10:00",
  "end_time": "11:00",
  "is_online": false,
  "location": "Gimnasio",
  "capacity": 4
}
```

---

## Reservas

### Buscar entrenadores con disponibilidad

```
GET /api/available-coaches
```

> Devuelve entrenadores que tienen al menos una disponibilidad puntual futura sin reservar.

### Listar reservas del usuario

```
GET /api/bookings
```

> Devuelve reservas según si el usuario es `Student` o `Coach`.

### ➕ Crear una nueva reserva

```
POST /api/bookings
```

**Parámetros:**

- `coach_id` (requerido)
- `sport_id` (requerido)
- `specific_availability_id` (requerido)
- `session_at` (requerido, formato `YYYY-MM-DDTHH:mm:ssZ`)

```json
{
  "coach_id": 1,
  "sport_id": 1,
  "specific_availability_id": 3,
  "session_at": "2025-07-01T10:00:00Z"
}
```

> La fecha y hora (`session_at`) **deben coincidir exactamente** con la disponibilidad puntual seleccionada.

> El precio se calcula desde `coach_sports.specific_price` si existe, o se usa `35 EUR` por defecto.

### Ver detalle de una reserva

```
GET /api/bookings/{id}
```

### Cancelar una reserva

```
PATCH /api/bookings/{id}/cancel
```

**Parámetros:**

- `cancelled_reason` (requerido) — Motivo de la cancelación.

```json
{
  "cancelled_reason": "No podré asistir"
}
```

> Solo puede cancelarse si no ha pasado la fecha de la sesión.

### Marcar como pagada (solo para coaches)

```
PATCH /api/bookings/{id}/mark-as-paid
```

> Aún no implementado por defecto, pero si se usa `markAsPaid()`, actualiza `payment_status` a `"Completado"`.

---

## Health Check

```
GET /api/health
```

(Público)

---

## Notas para Postman / entorno de pruebas

- Base URL: `https://fitcalendarbackendlaravel-production.up.railway.app`
- Autenticación: `Authorization: Bearer {{token}}`
- Headers comunes:

```http
Accept: application/json
Authorization: Bearer {{token}}
```

---

## 🔮 En desarrollo / pendientes

- `/api/coach/{id}/reviews`
- `/api/payments/...`
- `/api/admin/...`

---
```
