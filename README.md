# Jaya Tech PHP Challenge

This is a REST API project for a driver ride system, developed in PHP using Laravel 10.x.

## Requirements

- PHP 8.1 or higher
- Composer
- Docker (for database only)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd jaya-tech-desafio-php
```

2. Install PHP dependencies:
```bash
composer install
```

3. Set up the environment:
```bash
cp .env.example .env
```

4. Configure environment variables in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ridely
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run migrations:
```bash
php artisan migrate
```

7. Start the database and phpmyadmin on Docker Compose (optional):
```bash
docker compose up -d
```

8. Start the server:
```bash
php artisan serve
```

## API Endpoints

### Drivers

#### Register a new driver
```http
POST /api/drivers
Content-Type: application/json

{
    "name": "John Doe",
    "car": {
        "license_plate": "ABC1234",
        "model": "Toyota Corolla",
        "color": "Black"
    },
    "available": true
}
```

#### Delete a driver
```http
DELETE /api/drivers/{id}
```

#### Get allocated rides for a driver
```http
GET /api/drivers/{id}/get-rides
```

### Rides

#### Request a driver
```http
POST /api/rides/request-driver
Content-Type: application/json

{
    "passenger": {
        "name": "Jane Doe",
        "email": "jane@example.com"
    },
    "pick_up": "123 Main St",
    "drop_off": "456 Oak Ave"
}
```

Response:
```json
{
    "id": 1,
    "status": "REQUESTED",
    "drop_off": "456 Oak Ave",
    "pick_up": "123 Main St",
    "driver": {
        "name": "John Doe",
        "car": {
            "color": "Black",
            "license_plate": "ABC1234",
            "model": "Toyota Corolla"
        }
    }
}
```

#### Cancel a ride
```http
POST /api/rides/cancel-ride
Content-Type: application/json

{
    "id": 1
}
```

Response:
```json
{
    "id": 1,
    "status": "CANCELLED",
    "drop_off": "456 Oak Ave",
    "pick_up": "123 Main St"
}
```

#### Accept a ride
```http
POST /api/rides/accept-ride
Content-Type: application/json

{
    "id": 1
}
```

Response:
```json
{
    "id": 1,
    "status": "ACCEPTED",
    "drop_off": "456 Oak Ave",
    "pick_up": "123 Main St",
    "passenger": {
        "name": "Jane Doe",
        "email": "jane@example.com"
    }
}
```

#### Refuse a ride
```http
POST /api/rides/refuse-ride
Content-Type: application/json

{
    "id": 1
}
```

Response:
```json
{
    "id": 1,
    "status": "REFUSED",
    "drop_off": "456 Oak Ave",
    "pick_up": "123 Main St",
    "passenger": {
        "name": "Jane Doe",
        "email": "jane@example.com"
    }
}
```

#### Finish a ride
```http
POST /api/rides/finish-ride
Content-Type: application/json

{
    "id": 1,
    "price": 25.50
}
```

Response:
```json
{
    "id": 1,
    "status": "FINISHED",
    "drop_off": "456 Oak Ave",
    "price": 25.50,
    "passenger": {
        "name": "Jane Doe",
        "email": "jane@example.com"
    }
}
```

## Ride States

A ride can be in one of the following states:
- `REQUESTED`: Ride requested, waiting for acceptance
- `ACCEPTED`: Ride accepted by driver
- `FINISHED`: Ride completed
- `CANCELLED`: Ride cancelled
- `REFUSED`: Ride refused

## Ride Flow

1. Passenger requests a ride (`POST /api/rides/request-driver`)
2. System automatically assigns an available driver
3. Driver can:
   - Accept the ride (`POST /api/rides/accept-ride`)
   - Refuse the ride (`POST /api/rides/refuse-ride`)
4. If accepted, the ride starts
5. Driver can:
   - Finish the ride (`POST /api/rides/finish-ride`)
   - Cancel the ride (`POST /api/rides/cancel-ride`)

## Error Handling

The API returns the following HTTP status codes:
- `200`: Success
- `404`: Resource not found
- `500`: Internal server error
