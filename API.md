Tu documentación está bastante bien estructurada y clara. Solo necesita algunas **correcciones y mejoras** para reflejar fielmente el comportamiento actual de tu backend. Aquí va la versión ajustada, incluyendo:

* La lógica real que aplica con `specific_availabilities`, `coach_sports`, y el precio.
* La omisión temporal de disponibilidad recurrente (que mencionaste haber deshabilitado).
* Correcciones menores de gramática y estructura.

---

# 📘️ Documentación actualizada de la API de FitCalendar

## 🛡️ Autenticación

Todos los endpoints protegidos requieren autenticación mediante token Bearer (Laravel Sanctum).

### 🔐 Login

```
POST /api/login
```

**Parámetros:**

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

###  Registro

```
POST /api/register
```

**Parámetros:**

* `name`
* `email`
* `password`
* `password_confirmation`
* `role` ("Student", "Coach", "Admin")
* `language` (opcional, por defecto `"es"`)
* `age` (opcional)
* `description` (opcional)

**Respuesta:**

```json
{
  "token": "...",
  "user":  "..."
}
```

###  Logout

```
POST /api/logout
```

> Requiere token de autenticación

---

##  Perfil de Usuario

### Obtener perfil

```
GET /api/user/profile
```

### Actualizar perfil

```
PUT /api/user/profile
```

**Parámetros opcionales:**

* `age`
* `description`

---

##  Perfil de Entrenador

### Ver perfil del entrenador autenticado

```
GET /api/coach/profile
```

### Actualizar perfil

```
PUT /api/coach/profile
```

**Parámetros (todos opcionales):**

* `description`
* `city`
* `country`
* `coach_type`
* `organization_id`
* `payment_info`

### Asignar deportes al entrenador

```
POST /api/coach/sports
```

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

##  Disponibilidad puntual

> Actualmente la disponibilidad recurrente está desactivada.

### Ver todas las disponibilidades puntuales (propias o públicas)

```
GET /api/specific-availabilities
```

### Crear nueva disponibilidad puntual

```
POST /api/specific-availabilities
```

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

##  Reservas

###  Buscar entrenadores con disponibilidad

```
GET /api/available-coaches
```

> Devuelve entrenadores que tienen al menos una disponibilidad puntual futura sin reservar.

###  Listar reservas del usuario

```
GET /api/bookings
```

> Devuelve reservas según si el usuario es `Student` o `Coach`.

### ➕ Crear una nueva reserva

```
POST /api/bookings
```

**Parámetros:**

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

###  Ver detalle de una reserva

```
GET /api/bookings/{id}
```

###  Cancelar una reserva

```
PATCH /api/bookings/{id}/cancel
```

**Parámetro requerido:**

```json
{
  "cancelled_reason": "No podré asistir"
}
```

> Solo puede cancelarse si no ha pasado la fecha de la sesión.

###  Marcar como pagada (solo para coaches)

```
PATCH /api/bookings/{id}/mark-as-paid
```

> Aún no implementado por defecto, pero si se usa `markAsPaid()`, actualiza `payment_status` a `"Completado"`.

---

##  Health Check

```
GET /api/health
```

(Público)

---

##  Notas para Postman / entorno de pruebas

* Base URL: `https://fitcalendarbackendlaravel-production.up.railway.app`
* Autenticación: `Authorization: Bearer {{token}}`
* Headers comunes:

```http
Accept: application/json
Authorization: Bearer {{token}}
```

---

## 🔮 En desarrollo / pendientes

* `/api/coach/{id}/reviews`
* `/api/payments/...`
* `/api/admin/...`

---
