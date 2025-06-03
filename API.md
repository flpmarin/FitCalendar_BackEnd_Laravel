# 📘️ Documentación de la API de FitCalendar

## 🛡️ Autenticación

Todos los endpoints protegidos requieren autenticación mediante token Bearer (Sanctum).

### 🔐 Login

```
POST /api/login
```

**Parámetros:**

* `email`
* `password`

**Respuesta:**

```json
{
  "token": "token_de_autenticacion"
}
```

### 📝 Registro

```
POST /api/register
```

**Parámetros:**

* `name`
* `email`
* `password`
* `password_confirmation`
* `role` ("Student", "Coach", "Admin")
* `language` (**opcional**, por defecto: "es")
* `age` (**opcional**)
* `description` (**opcional**)

**Respuesta:** token + datos del usuario

### 🚪 Logout

```
POST /api/logout
```

*Requiere token de autenticación*

---

## 🗕️ Perfil de Usuario

### ✏️ Obtener perfil

```
GET /api/user/profile
```

*Token Bearer requerido*

### ✏️ Actualizar perfil

```
PUT /api/user/profile
```

**Parámetros opcionales:**

* `age`
* `description`

---

## 👤 Perfil de Entrenador

### Obtener perfil

```
GET /api/coach/profile
```

*Token de Coach requerido*

### Actualizar perfil

```
PUT /api/coach/profile
```

**Parámetros (todos opcionales):**

* `description`, `city`, `country`, `coach_type`, `organization_id`, `payment_info`

### Asignar deportes

```
POST /api/coach/sports
```

**Parámetros:**

```json
{
  "sports": [
    {
      "id": 1,                      // requerido
      "specific_price": 35.00,      // opcional
      "specific_location": "Centro Deportivo", // opcional
      "session_duration_minutes": 60 // opcional
    }
  ]
}
```

---

## 🗓️ Disponibilidad

### 🗖️ Disponibilidad recurrente

#### `GET /api/availability-slots`

Lista todas las franjas recurrentes del entrenador autenticado.

#### `POST /api/availability-slots`

Crea una nueva franja recurrente.

```json
{
  "sport_id": 1,            // requerido
  "weekday": 1,             // requerido
  "start_time": "10:00",   // requerido
  "end_time": "11:00",     // requerido
  "is_online": false,       // requerido
  "location": "Gimnasio",  // opcional
  "capacity": 4             // opcional
}
```

#### `GET /api/availability-slots/{id}`

Muestra detalles de una franja.

#### `PUT /api/availability-slots/{id}`

Actualiza una franja recurrente (parámetros iguales al POST).

#### `DELETE /api/availability-slots/{id}`

Elimina una franja recurrente.

#### `GET /api/coaches/{coachId}/availability-slots`

Ver disponibilidad pública de un entrenador.

### 📊 Disponibilidad puntual

#### `GET /api/specific-availabilities`

Lista los slots disponibles (públicos o propios).

#### `POST /api/specific-availabilities`

Crear slot puntual.

```json
{
  "sport_id": 1,             // requerido
  "date": "2025-07-01",      // requerido
  "start_time": "10:00",     // requerido
  "end_time": "11:00",       // requerido
  "is_online": false,         // opcional
  "location": "Gimnasio",    // opcional
  "capacity": 4,              // opcional
  "price": 30.0,              // opcional
  "duration_minutes": 60      // opcional
}
```

#### `PATCH /api/specific-availabilities/{id}/book`

Marcar como reservado.

---

## 🗕 Reservas

### 🔍 Buscar entrenadores

```
GET /api/available-coaches
```

(Público)

### 📋 Listar reservas del usuario

```
GET /api/bookings
```

### ➕ Crear reserva puntual

```
POST /api/bookings
```

**Parámetros:**

```json
{
  "coach_id": 1,                  // requerido
  "sport_id": 1,                  // requerido
  "specific_availability_id": 3,  // requerido
  "session_at": "2025-07-01T10:00:00Z" // requerido
}
```

### 📖 Ver detalle de reserva

```
GET /api/bookings/{id}
```

### ❌ Cancelar reserva

```
PATCH /api/bookings/{id}/cancel
```

**Parámetro requerido:** `cancelled_reason`

### ✅ Marcar como pagada

```
PATCH /api/bookings/{id}/mark-as-paid
```

(Solo Coach)

---

## ⚙️ Health Check

```
GET /api/health
```

(Público)

---

## 🤝 Notas para PoC / Postman

* URL base: `https://fitcalendarbackendlaravel-production.up.railway.app`
* Token: `{{token}}`
* Todas las llamadas deben incluir headers:

    * `Accept: application/json`
    * `Authorization: Bearer {{token}}` (cuando aplique)

---

## 📂 Otros endpoints planeados (futuro)

* `/api/coach/{id}/reviews` (obtener valoraciones)
* `/api/payments/...` (procesamiento de pagos)
* `/api/admin/...` (gestión interna)

---

📄 Esta documentación está alineada con la colección Postman: `FitCalendar API - Railway`
