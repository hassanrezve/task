# Task Management System

A Laravel-based task management system with a hierarchical role-based structure (Admin → Manager → User), task CRUD operations, and comment functionality.

## Features
- Modern UI with Bootstrap, jQuery, HTML, and CSS
- Backend with Laravel 12.3.0 (task management, hierarchical user roles, Eloquent models)
- MySQL database with CRUD for tasks, users, categories, and comments
- Hierarchical role-based access:
  - Admins can assign tasks to Managers
  - Managers can assign tasks to Users
  - Users can view their tasks and add comments
- User roles (`admin`, `manager`, `user`) are enforced using a MySQL `enum` in the database and a PHP `enum` in the code for type safety
- `Layout` component handles page titles and headings for a cleaner view structure
- `Navbar` component for a reusable navigation bar
- Ajax form submission for creating, editing tasks, and adding comments without page reload
- iziToast for displaying success and error notifications (loaded via CDN)
- Validation errors displayed under each form field without page reload
- JavaScript organized in a `@section('scripts')` block and appended to the layout
- Assets compiled and served using Vite

## Setup Instructions
1. Clone the repository:


2. Install dependencies:
composer update

3. Set up the environment:
- Copy `.env.example` to `.env`
- Update `.env` with your database credentials
- Generate an application key:


4. Run migrations and seed the database:
php artisan migrate:fresh
php artisan db:seed

6. Start the Laravel development server:
7. Visit `http://localhost:8000` in your browser.

## Demo
- Admin: `admin@example.com` / `password` (Can assign tasks to Managers)
- Manager: `manager@example.com` / `password` (Can assign tasks to Users)
- User: `user@example.com` / `password` (Can view assigned tasks and comment)

## Video Walkthrough
[Add a link to a video walkthrough if available]