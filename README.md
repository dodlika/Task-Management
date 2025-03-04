Task Management System
A web application built with Laravel that allows users to register, log in, and manage their tasks with features like status tracking, filtering, and soft deletion.
Features

User registration and authentication (Laravel's built-in auth)
CRUD operations for tasks
Task statuses (Pending, In Progress, Completed, On Hold)
Task priorities (Low, Medium, High, Critical)
Due date tracking
Task filtering by status and priority
Search functionality
Soft delete for tasks

Requirements

PHP 8.2 or higher
Composer
Node.js and NPM

Installation

Clone the repository
git clone https://github.com/dodlika/Task-Management.git
cd Task-Management
Install PHP dependencies
composer install
Install JavaScript dependencies
npm install
Copy the environment file
cp .env.example .env
Generate application key
php artisan key:generate
Set up SQLite database (using SQLite for easy setup)
touch database/database.sqlite
Update .env file to use SQLite
DB_CONNECTION=sqlite
Leave DB_DATABASE empty to use the default path
DB_DATABASE=database/database.sqlite
Create sessions table and run migrations
php artisan session:table
php artisan migrate
Seed the database with sample data (optional)
php artisan db:seed
Build frontend assets
npm run build

Running the Application

Start the development server
php artisan serve
Visit http://localhost:8000 in your browser
Default test user (if you ran the seeder):


Usage

Register a new account 
Create, view, update, and delete tasks from your dashboard
Filter tasks by status or priority
Search tasks by title or description

Troubleshooting

Sessions table error: If you encounter an error about missing sessions table, run:
php artisan session:table
php artisan migrate
Permission issues: Make sure the storage and bootstrap/cache directories are writable:
chmod -R 775 storage
chmod -R 775 bootstrap/cache
Blank page or 500 error: Check the Laravel logs at storage/logs/laravel.log
Database issues: If you have problems with the SQLite database:
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate:fresh

Development

For active development with auto-reload:
npm run dev
In a separate terminal, run the Laravel server:
php artisan serve