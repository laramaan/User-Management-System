# User Management System

This project is a **User Management System** built with Laravel 10, following a **DAO, BO, and Service Layer architecture**. The application supports creating, updating, and retrieving user information with robust validation, error handling, and caching.

---

## Features

- **CRUD Operations**: Create, update, and retrieve users.
- **Validation**: Input validation at the request level for secure and consistent data handling.
- **Caching**: Reduces repetitive database queries to improve performance.
- **Layered Architecture**: DAO, BO, and Service layers for separation of concerns.
- **Unit Testing**: PHPUnit tests to ensure functionality.

---

## Prerequisites

Ensure you have the following installed on your system:

- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **MySQL**: >= 5.7 or any supported database

---

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>

2. Install PHP dependencies:
   ```bash
   composer install

3. Create a .env file Copy the example .env file and update the following variables in your .env file:
   ```bash
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password

4. Run database migrations:
   ```bash
   php artisan migrate

5. Start the development server:
   ```bash
   php artisan serve

## API Endpoints

All endpoints are prefixed with /api/v1.

## Folder Structure
app/
├── DAO/                # Data Access Object layer
├── BO/                 # Business Object layer
├── Services/           # Service layer
├── Http/
│   ├── Controllers/    # API Controllers
│   ├── Requests/       # Request validation
