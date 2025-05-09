# 📷 Upload Service (Laravel Microservice)

This microservice is responsible for securely uploading images to the server using JWT-based authentication. It is part of a microservices architecture and relies on a separate **Auth Service** for issuing JWT tokens.

---

## 🚀 Features

- JWT Authentication via RS256 public/private key pair
- Secure image upload via `POST /api/upload`
- Middleware protection with custom JWT verification
- Dockerized for easy deployment
- PHPUnit test coverage

---

## 📁 Folder Structure Overview

```
upload-service/
├── app/
├── tests/
├── routes/api.php
├── app/Http/Controllers/UploadController.php
├── app/Http/Middleware/JwtMiddleware.php
├── Dockerfile
├── docker-compose.yml
└── keys/jwt_public.key
```

---

## 🛠 Requirements

- Docker & Docker Compose
- Laravel 10+ (or higher)
- JWT token issued by [Auth Service](http://auth-service:8000)
- RS256 public key provided via `.env` or Docker volume

---

## ⚙️ Environment Variables

You can configure these in `.env` or pass via Docker:

```env
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:YourAppKeyHere

JWT_PUBLIC_KEY_PATH=/var/www/html/keys/jwt_public.key
```

---

## 🐳 Docker Setup

### 1. Build and Run Upload Service

```bash
docker compose up --build upload
```

The service will be available at: `http://localhost:8081`

---

## 🔐 JWT Authentication

### Middleware

All image upload requests must include a valid JWT token in the `Authorization` header:

```http
Authorization: Bearer <your_jwt_token>
```

The token is verified using the `jwt.auth` middleware (`JwtMiddleware.php`) and the public key defined in `JWT_PUBLIC_KEY_PATH`.

---

## 📦 API Endpoint

### `POST /api/upload`

**Headers:**
```http
Authorization: Bearer <jwt_token>
```

**Form Data:**
- `image`: A valid image file (jpg, png, etc.)

**Response:**
```json
{
  "message": "Image uploaded successfully.",
  "path": "uploads/photo.jpg"
}
```

---

## ✅ Automated Tests

Run feature tests using:

```bash
php artisan test
```

Tests include:
- Unauthorized access without JWT
- Valid upload with correct JWT issued by Auth Service

---

## 🧪 Sample Test Snippet

```php
$response = $this->postJson('/api/upload', [
    'image' => UploadedFile::fake()->image('photo.jpg'),
], [
    'Authorization' => 'Bearer ' . $jwt,
]);

$response->assertStatus(200)
    ->assertJsonStructure(['message', 'path']);
```

---

## 🔐 Public Key Location

Ensure the JWT public key is mounted to the container:

```yaml
volumes:
  - ./keys/jwt_public.key:/var/www/html/keys/jwt_public.key:ro
```

Or manually place it in `storage/` and point `JWT_PUBLIC_KEY_PATH` to that path.

---

## 📷 Uploaded Files

Uploaded images are stored in:

```
storage/app/public/uploads/
```

Ensure you run:

```bash
php artisan storage:link
```

To make them accessible via `public/storage/uploads/`.

---

## 🤝 Integration with Auth Service

This service expects JWTs issued by the **Auth Service** (e.g., at `http://auth-service:8000/api/login`).

Make sure both services share the same key pair:
- Auth Service uses **private key** to sign
- Upload Service uses **public key** to verify

---

## 🧼 License

MIT — Free to use and modify.
