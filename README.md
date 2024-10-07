Title: News Aggregator API.

Description: # News Aggregator API
This project is a RESTful API for a news aggregator service that pulls articles from various sources and provides endpoints for frontend applications to consume. The API allows users to authenticate, search for articles, and set preferences for their news feed.

## Technologies Used
- Laravel 9
- MySQL 8
- Docker
- Composer
- Swagger (for API documentation)

## Setup Instructions

### Prerequisites
- Docker and Docker Compose installed
- Composer installed
- MySQL installed (or use Docker for MySQL)

### Steps to Run Locally
1. Clone the repository:
   ```bash
   git clone https://github.com/reniz/laravel-innoscripta.git
   cd news-aggregator-api
   ```

2. Copy the `.env.example` file and update environment variables:
   ```bash
   cp .env.example .env
   ```

3. Build and run the Docker containers:
   ```bash
   docker-compose up --build
   ```

4. Run database migrations:
   ```bash
   docker exec -it laravel_app bash
   php artisan migrate
   ```

5. Access the API at `http://localhost:8080`.

## API Documentation
The API documentation is generated using Swagger. You can access it at:
http://localhost:8080/api/docs

### Key API Endpoints
- `POST /api/register` - User Registration
- `POST /api/login` - User Login
- `GET /api/articles` - Get all articles with pagination and filtering
- `GET /api/articles/{id}` - Get a single article
- `POST /api/preferences` - Set user preferences for news categories and sources

## Environment Variables

To run this project, you will need to add the following environment variables in your `.env` file:

- `DB_CONNECTION=mysql`
- `DB_HOST=db`
- `DB_PORT=3306`
- `DB_DATABASE=laravel`
- `DB_USERNAME=root`
- `DB_PASSWORD=secret`

## Running Tests
To run the automated tests for the project:
```bash
php artisan test

## Project Structure
- `app/Http/Controllers` - Contains the API controllers
- `app/Models` - Contains the models (e.g., Article, User)
- `routes/api.php` - Defines the API routes
- `tests/` - Contains automated tests

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request.



