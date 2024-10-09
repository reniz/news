# News Aggregator API

This repository contains the codebase for the **News Aggregator API**, a Laravel-based RESTful API for a news aggregator service that pulls articles from various sources and provides endpoints for frontend applications to consume. The API allows users to authenticate, search for articles, and set preferences for their news feed.

## Table of Contents
- [Setup Instructions](#setup-instructions)
- [Running Docker Environment](#running-docker-environment)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Additional Notes](#additional-notes)

## Setup Instructions

## Technologies Used
- Laravel 
- MySQL 
- Docker
- Composer
- Swagger (for API documentation)

### Prerequisites
Make sure you have the following installed:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Composer](https://getcomposer.org/)

### Installation Steps
1. **Clone the Repository**:
   ```sh
   git clone https://github.com/reniz/laravel-innoscripta.git
   ```

2. **Install Dependencies**:
   Run Composer to install all necessary dependencies.
   ```sh
   composer install
   ```

3. **Environment Setup**:
   Copy the `.env.example` file to `.env` and configure the environment variables, including database settings and API keys.
   ```sh
   cp .env.example .env
   ```

4. **Generate Application Key**:
   ```sh
   php artisan key:generate
   ```

5. **Database Migration**:
   Run the following command to create the necessary database tables.
   ```sh
   php artisan migrate
   ```

6. **Seed the Database**:
   You can seed the database with sample articles using:
   ```sh
   php artisan db:seed
   ```

## Running Docker Environment

This project is containerized using Docker for easy setup. Follow these steps to run the application in a Docker environment:

1. **Start Docker Containers**:
   Run the following command to start the Docker containers, including the web server, database, and other services.
   ```sh
   docker-compose up -d
   ```

2. **Access the Application**:
   The application will be available at `http://localhost:8080`.

3. **Run Scheduled Tasks**:
   The project uses a Laravel scheduler to fetch articles from external APIs periodically. To start the scheduler within the Docker container:
   ```sh
   docker exec -it laravel_app php artisan schedule:work
   ```

## API Documentation

API documentation is provided using Swagger/OpenAPI. You can access the Swagger UI by navigating to the following link after starting the application:

- [API Documentation](http://localhost:8080/api/documentation)

The documentation includes all available endpoints, request parameters, and response formats for ease of use.

## Testing

Unit and feature tests are available for validating the core functionalities of the API.

1. **Run Tests**:
   To run the test suite, execute the following command:
   ```sh
   php artisan test
   ```

## Additional Notes

- **Caching**: The API uses caching to improve performance, especially for frequently requested articles.
- **Security**: Proper authentication and authorization checks are implemented for all protected routes, utilizing Laravel Sanctum.
- **Rate Limiting**: Rate limiting is also implemented to ensure fair usage and prevent abuse of the endpoints.

## Project Structure
- `app/Http/Controllers` - Contains the API controllers
- `app/Models` - Contains the models (e.g., Article, User)
- `routes/api.php` - Defines the API routes
- `tests/` - Contains automated tests




