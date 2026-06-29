# Mini E-commerce Application (DEMO)

## Overview

This project is a simple e-commerce application built as part of a technical assignment.

The application allows users to browse products, add them to a shopping cart, adjust quantities, and place orders. Orders are validated on both the frontend and backend before being persisted to a MySQL database.

The backend follows a layered architecture using Laravel's MVC pattern together with DTOs, service classes, and request validation to keep responsibilities separated.

## Features

- Product listing
- Product descriptions
- Sale pricing with automatically calculated discount percentages
- Shopping cart
- Adjustable item quantities
- Frontend validation
- Backend validation
- Order creation
- MySQL persistence
- Translation-ready frontend (English included)
- Automated tests
- Dockerized development environment

## Technology Stack

### Backend

- Laravel 12
- PHP 8.4
- MySQL

### Frontend

- Vue 3
- TypeScript
- Vite

### Infrastructure

- Docker Compose

---

## Running the project

### Prerequisites

- Git
- Docker Desktop

### 1. Clone the repository

```bash
git clone https://github.com/T-Lahtinen/mini-ecommerce.git
cd mini-ecommerce
```

### 2. Initial setup

Create a local environment file:

```bash
cp .env.example .env
```

Start the Docker containers:
NOTE! If the build initially fails, try the same command again.

```bash
docker compose up -d --build
```

Generate the Laravel application key:

```bash
docker compose exec app php artisan key:generate
```

Run the database migrations and seed sample data:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

### 3. Access the application

Open:

```
http://localhost:8000
```

To run the test suite:

```
docker compose exec app php artisan test
```

---

## API Endpoints

### List products

```
GET /api/products
```

### Create order

```
POST /api/orders
```

Example request:

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "customer_email": "john@example.com",
    "items": [
        {
            "product_no": "PROD-KEYBOARD-001",
            "quantity": 2
        }
    ]
}
```

The client only submits customer information, product numbers, and quantities. Product prices and totals are calculated on the server to ensure data integrity.

---

## Project Structure

```
app/
├── Data/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Models/
└── Services/

database/
├── migrations/
└── seeders/

resources/
└── js/
    ├── components/
    ├── lang/
    └── types/
```

---

## Architecture

The backend follows a layered architecture:

- **Controllers** handle HTTP requests and responses.
- **Request classes** validate incoming data.
- **DTOs** transfer validated data through the application.
- **Services** contain the business logic.
- **Models** handle persistence using Eloquent ORM.

This separation keeps the application modular, testable, and easier to maintain.
