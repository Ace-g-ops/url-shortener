# URL Shortener API

## Overview
This project is a robust URL shortening service built with the Laravel 12 framework, providing a RESTful API for managing and tracking shortened links. It incorporates user authentication using Laravel Sanctum and persists data in a MariaDB database.

## Features
- Laravel: Provides a solid foundation for backend API development with its elegant syntax and comprehensive features.
- Sanctum: Implements token-based authentication for secure access to user-specific URL management functionalities.
- MariaDB: Utilized as the primary relational database for efficient storage and retrieval of user and URL data.
- URL Shortening: Generates unique, compact short codes for lengthy URLs, enabling easy sharing and management.
- Click Tracking: Automatically records and increments the click count for each shortened URL upon access, providing basic analytics.

## Getting Started
### Installation
To set up the project locally, follow these steps:

```bash
# 1. Clone the repository
git clone https://github.com/Ace-g-ops/url-shortener.git

# 2. Navigate into the project directory
cd url-shortener

# 3. Install PHP dependencies using Composer
composer install

# 4. Copy the environment example file and generate an application key
cp .env.example .env
php artisan key:generate

# 5. Configure your .env file with database credentials and other settings.
#    Then run database migrations to set up the database schema.
php artisan migrate

# 6. Install Node.js dependencies (for frontend assets, though this is a backend project)
npm install

# 7. Build frontend assets
npm run build

# 8. Start the Laravel development server
php artisan serve
```

### Environment Variables
The following environment variables are required for the application to function correctly. Create a `.env` file in the project root and populate it with these variables.

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=your_mariadb_root_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Laravel Sanctum configuration
SANCTUM_STATEFUL_DOMAINS="localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1"
SANCTUM_TOKEN_PREFIX=""

# AWS (if used)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## API Documentation
### Base URL
`http://localhost:8000/api`

### Endpoints
#### POST /api/register
Registers a new user account.

**Request**:
```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "StrongPassword123"
}
```

**Response**:
```json
{
  "user": {
    "name": "John Doe",
    "email": "john.doe@example.com",
    "updated_at": "2023-10-27T10:00:00.000000Z",
    "created_at": "2023-10-27T10:00:00.000000Z",
    "id": 1
  },
  "token": "YOUR_AUTH_TOKEN_HERE"
}
```

**Errors**:
- `422 Unprocessable Entity`: Validation failed for input data (e.g., missing fields, invalid email format, password too short).
- `409 Conflict`: Email already exists (if a unique constraint is set and caught).

#### POST /api/login
Authenticates a user and returns an API token.

**Request**:
```json
{
  "email": "john.doe@example.com",
  "password": "StrongPassword123"
}
```

**Response**:
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "email_verified_at": null,
    "created_at": "2023-10-27T10:00:00.000000Z",
    "updated_at": "2023-10-27T10:00:00.000000Z"
  },
  "token": "YOUR_AUTH_TOKEN_HERE"
}
```

**Errors**:
- `401 Unauthorized`: Invalid credentials (email or password incorrect).
- `422 Unprocessable Entity`: Validation failed for input data (e.g., missing email/password).

#### POST /api/logout
Invalidates the current user's API token, effectively logging them out.

**Requires Authentication**: `Bearer Token` in `Authorization` header.

**Request**:
(No request body required)

**Response**:
```json
{
  "message": "Logged out successfully"
}
```

**Errors**:
- `401 Unauthorized`: No valid authentication token provided.

#### GET /api/url
Retrieves all shortened URLs belonging to the authenticated user.

**Requires Authentication**: `Bearer Token` in `Authorization` header.

**Request**:
(No request body required)

**Response**:
```json
[
  {
    "id": 1,
    "user_id": 1,
    "original_url": "https://www.google.com",
    "short_code": "abcd12",
    "click_count": 5,
    "created_at": "2023-10-27T10:05:00.000000Z",
    "updated_at": "2023-10-27T10:10:00.000000Z"
  },
  {
    "id": 2,
    "user_id": 1,
    "original_url": "https://docs.laravel.com",
    "short_code": "efgh34",
    "click_count": 2,
    "created_at": "2023-10-27T10:15:00.000000Z",
    "updated_at": "2023-10-27T10:20:00.000000Z"
  }
]
```

**Errors**:
- `401 Unauthorized`: No valid authentication token provided.

#### GET /api/url/{id}
Retrieves a specific shortened URL by its ID for the authenticated user.

**Requires Authentication**: `Bearer Token` in `Authorization` header.

**Request**:
(No request body required, `id` is a path parameter)

**Response**:
```json
{
  "id": 1,
  "user_id": 1,
  "original_url": "https://www.google.com",
  "short_code": "abcd12",
  "click_count": 5,
  "created_at": "2023-10-27T10:05:00.000000Z",
  "updated_at": "2023-10-27T10:10:00.000000Z"
}
```

**Errors**:
- `404 Not Found`: URL with the given ID was not found or does not belong to the authenticated user.
- `401 Unauthorized`: No valid authentication token provided.

#### DELETE /api/url/{id}
Deletes a specific shortened URL by its ID for the authenticated user.

**Requires Authentication**: `Bearer Token` in `Authorization` header.

**Request**:
(No request body required, `id` is a path parameter)

**Response**:
```json
{
  "message": "URL deleted successfully"
}
```

**Errors**:
- `404 Not Found`: URL with the given ID was not found or does not belong to the authenticated user.
- `401 Unauthorized`: No valid authentication token provided.

#### POST /api/shorten
Creates a new shortened URL for the authenticated user.

**Requires Authentication**: `Bearer Token` in `Authorization` header.

**Request**:
```json
{
  "url": "https://long-example.com/some/very/long/path/to/a/resource"
}
```

**Response**:
```json
{
  "original_url": "https://long-example.com/some/very/long/path/to/a/resource",
  "short_url": "http://localhost:8000/xyz789",
  "short_code": "xyz789",
  "created_at": "2023-10-27T10:30:00.000000Z"
}
```

**Errors**:
- `422 Unprocessable Entity`: Validation failed for input data (e.g., `url` is missing or invalid format).
- `401 Unauthorized`: No valid authentication token provided.

#### GET /{short_code}
Redirects to the original URL associated with the provided short code. This is a web route.

**Request**:
(No request body required, `short_code` is a path parameter)

**Response**:
- `302 Found`: Redirects to the `original_url`.
  (The HTTP response will be a redirect to the original URL, not a JSON payload.)

**Errors**:
- `404 Not Found`: Short code does not exist in the database.

[![Readme was generated by Dokugen](https://img.shields.io/badge/Readme%20was%20generated%20by-Dokugen-brightgreen)](https://www.npmjs.com/package/dokugen)