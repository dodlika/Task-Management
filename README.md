# Task Management System

A web application built with Laravel that allows users to register, log in, and manage their tasks with features like status tracking, filtering, and soft deletion.

## Features

- User registration and authentication (Laravel's built-in auth)
- CRUD operations for tasks
- Task statuses (Pending, In Progress, Completed, On Hold)
- Task priorities (Low, Medium, High, Critical)
- Due date tracking
- Task filtering by status and priority
- Search functionality
- Soft delete for tasks

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM

## Installation

1. Clone the repository
   git clone https://github.com/dodlika/Task-Management.git
   cd Task-Management

2. Install PHP dependencies
   composer install

3. Install JavaScript dependencies
   npm install

4. Copy the environment file
   cp .env.example .env

5. Generate application key
   php artisan key:generate

6. Set up SQLite database (using SQLite for easy setup)
   touch database/database.sqlite

7. Update .env file to use SQLite
   DB_CONNECTION=sqlite
   # Leave DB_DATABASE empty to use the default path
   DB_DATABASE=database/database.sqlite

8. Create and run migrations
   php artisan migrate

9. Seed the database with sample data (OPTIONAL)
   php artisan db:seed
   
   This will create a test user and sample tasks:
   - Test User: test@example.com / password
   - 10+ sample tasks with various statuses and priorities

10. Build frontend assets
    npm run dev
    
    Note: Use npm run dev instead of npm run build as it works more reliably.

## Running the Application

1. Start the development server
   php artisan serve

2. Visit http://localhost:8000 in your browser

3. If you ran the optional seeder, you can log in with:
   - Email: test@example.com
   - Password: password
   
   Or register a new account

## Usage

1. Register a new account or use the default test account
2. Create, view, update, and delete tasks from your dashboard
3. Filter tasks by status or priority
4. Search tasks by title or description

## Troubleshooting

- Sessions table error: If you encounter an error about missing sessions table, run:
  php artisan session:table
  php artisan migrate

- Permission issues: Make sure the storage and bootstrap/cache directories are writable:
  chmod -R 775 storage
  chmod -R 775 bootstrap/cache

- Blank page or 500 error: Check the Laravel logs at storage/logs/laravel.log

- Database issues: If you have problems with the SQLite database:
  rm database/database.sqlite
  touch database/database.sqlite
  php artisan migrate:fresh

- Build issues: If npm run build gives errors, use npm run dev instead for development

## Development

1. For active development with auto-reload:
   npm run dev

2. In a separate terminal, run the Laravel server:
   php artisan serve

## License

This project is open-sourced software licensed under the MIT license (https://opensource.org/licenses/MIT).