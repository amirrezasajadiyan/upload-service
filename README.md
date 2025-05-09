## 🛠 Tech Stack

- Laravel 12
- PHP 8.4
- JWT Auth (RSA Public Key Verification)
- Docker & Docker Compose
- File Uploads via `storage/app/public`
- PHPUnit for automated tests

## 🚀 Quick Start

### 1. Clone the project

```bash
git clone https://github.com/amirrezasajadiyan/upload-service.git
cd upload-service
```

### 2. Create .env file

```bash
cp .env.example .env
```

Then configure the .env file:

```env
APP_NAME=UploadService
APP_URL=http://localhost:8001

FILESYSTEM_DISK=public

JWT_PUBLIC_KEY=http://auth-service:8000/api/public-key
JWT_ALGO=RS256

AUTH_SERVICE_URL=http://auth-service:8000
```

> Note: The JWT_PUBLIC_KEY points to the auth-service endpoint that returns the public key.

### 🐳 Run with Docker Compose

```bash
docker-compose -f docker-compose.upload.yml up --build
```

This will:

- Build and start the Upload Service
- Automatically run `php artisan test`
- Start Laravel on port `8001`

### 📡 API Endpoints

| Method | Endpoint     | Middleware   | Description                    |
|--------|--------------|--------------|--------------------------------|
| POST   | `/api/upload`| `jwt.auth`   | Upload image with JWT          |

### ✅ Run Tests

To manually run tests inside the container:

```bash
docker-compose exec upload-service php artisan test
```

### 📁 Project Structure (important files)

```
upload-service/
├── app/
│   └── Http/
│       └── Controllers/
│           └── UploadController.php
├── routes/
│   └── api.php
├── Dockerfile
├── docker-compose.upload.yml
├── tests/
│   └── Feature/
│       └── UploadTest.php
```

### 🔗 Integration with Auth Service

- Upload service fetches the JWT public key from `auth-service`.
- Validates JWT tokens in the `Authorization: Bearer <token>` header.
- Ensure both services use the shared Docker network.

### 🧪 Development Status

- ✅ Secure file upload
- ✅ JWT token validation
- ✅ Public key fetching from auth-service
- ✅ Automated tests
- ✅ Integration-ready with auth-service

---

## 📁 Suggested Project Structure

```
microservices-root/
├── auth-service/
│ ├── Dockerfile
│ ├── docker-compose.auth.yml
│ └── ...
├── upload-service/
│ ├── Dockerfile
│ ├── docker-compose.upload.yml
│ └── ...
├── docker-compose.yml ← connects both services together
```

## 🐳 Root `docker-compose.yml`

```yaml
version: "3.8"
services:
  auth-service:
    build:
      context: ./auth-service
    container_name: auth-service
    ports:
      - "8000:8000"
    volumes:
      - ./auth-service:/var/www/html
    depends_on:
      - auth-db
    networks:
      - microservice

  upload-service:
    build:
      context: ./upload-service
    container_name: upload-service
    ports:
      - "8001:8001"
    volumes:
      - ./upload-service:/var/www/html
    depends_on:
      - auth-service
    networks:
      - microservice

  auth-db:
    image: mysql:8
    container_name: auth-db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: auth_db
    ports:
      - "3307:3306"
    networks:
      - microservice

networks:
  microservice:
    driver: bridge
```

### 🐳 docker-compose.auth.yml

```yaml
version: "3.8"
services:
  auth-service:
    build: .
    container_name: auth-service
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - auth-db
    networks:
      - microservice

  auth-db:
    image: mysql:8
    container_name: auth-db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: auth_db
    ports:
      - "3307:3306"
    networks:
      - microservice

networks:
  microservice:
    driver: bridge
```

### 🐳 docker-compose.upload.yml

```yaml
version: "3.8"
services:
  upload-service:
    build: .
    container_name: upload-service
    ports:
      - "8001:8001"
    volumes:
      - .:/var/www/html
    depends_on:
      - auth-service
    networks:
      - microservice

networks:
  microservice:
    driver: bridge
```
```
