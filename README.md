<p align="center">
  <a href="" rel="noopener">
    <img width="200px" height="200px" src="/images/logosudinv2.png" alt="SUDIN PPAPP logo">
  </a>
</p>

<h3 align="center">Digital application for contraceptive management at the PPAPP Sub-dept., East Jakarta</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](/LICENSE)

</div>

<p align="center"> 
    System Alokon is a robust web-based application built using Laravel 11 and Filament 3, which provides a modern and scalable platform for managing goods transactions. It features a responsive design, a role-based access control system, and powerful data management tools. This system includes functionalities for recording incoming and outgoing goods transactions and offers seamless integration with a letter generation feature to streamline transaction documentation.
    <br> 
</p>

## 📝 Table of Contents
- [About](#about)
- [System Requirements](#system_requirements)
- [Getting Started](#getting_started)
- [Development Setup](#development_setup)
- [Database Setup](#database_setup)
- [Running Tests](#tests)
- [Deployment](#deployment)
- [Built Using](#built_using)
- [Authors](#authors)

## 🧐 About <a name = "about"></a>

System Alokon is developed to simplify and modernize the process of managing logistics within organizations. Built on the Laravel 11 framework, it leverages the power of Filament 3 for a user-friendly admin panel and MySQL for reliable data storage. The system is designed to handle the recording of incoming and outgoing goods, track transactions efficiently, and produce transaction letters in a standardized format. 

Key technologies used include:
- **Laravel 11**: A high-performance PHP framework for backend logic and data processing.
- **Filament 3**: A lightweight and flexible admin panel for streamlined user and data management.
- **MySQL**: A robust relational database for storing and retrieving transaction data.

This application is suitable for organizations that require an efficient and secure solution to manage their goods logistics while offering scalability and customization for future enhancements.

## 💻 System Requirements <a name = "system_requirements"></a>

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Laragon (Latest version)
- Browser = Google Chrome, Microsoft Edge
- Git

## 🏁 Getting Started <a name = "getting_started"></a>

### Installing Laragon

1. Download Laragon Full version from [https://laragon.org/download/](https://laragon.org/download/)
2. Install Laragon by running the installer
3. During installation:
   - Choose your preferred installation directory
   - Select "Run Laragon when Windows starts" if desired
   - Complete the installation

## 🔧 Development Setup <a name = "development_setup"></a>

### Clone & Install

```bash
# Navigate to Laragon www directory
cd C:/laragon/www

# Clone the repository
git clone https://github.com/Hafiffdl/filament-app.git

# Navigate to project directory
cd filament-app

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Environment Configuration

Edit `.env` file and update these settings:

```env
APP_NAME="Your App Name"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://project-name.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

## 📦 Database Setup <a name = "database_setup"></a>

```bash
# Create database tables
php artisan migrate
```

### Install Filament

```bash
# Install Filament
composer require filament/filament:"^3.2"

# Publish Filament configuration
php artisan filament:install --panels

# Create admin user
php artisan make:filament-user
```

### Additional Setup

```bash
# Optimize autoloader
composer dump-autoload -o

# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache
```

## 🎈 Development Workflow <a name="workflow"></a>

1. Start Laragon services:
   - Open Laragon
   - Click "Start All"

2. Access the application:
   - Admin Panel: http://filament-app.test/admin
   
3. Default admin credentials:
   - Email: admin@gmail.com
   - Password: password

## Folder Structure

```
├── app/
│   ├── Filament/         # Filament resource files
│   ├── Http/             # Controllers, Middleware
│   ├── Models/           # Eloquent models
│   └── Providers/        # Service providers
├── config/              # Configuration files
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/        # Database seeders
├── public/             # Publicly accessible files
├── resources/
│   └── views/          # Blade templates
├── routes/             # Route definitions
└── storage/           # Application storage
```

## 🔧 Running Tests <a name = "tests"></a>

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 🚀 Deployment <a name = "deployment"></a>

For production deployment:

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Optimize configuration loading
php artisan config:cache

# Optimize route loading
php artisan route:cache

# Optimize view loading
php artisan view:cache
```

## Maintenance Commands

```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Reset all caches
php artisan optimize:clear
```

## ⛏️ Built Using <a name = "built_using"></a>

- [Laravel 11](https://laravel.com) - PHP Framework
- [Filament 3](https://filamentphp.com) - Admin Panel
- [MySQL](https://www.mysql.com) - Database
- [Laragon](https://laragon.org) - Development Environment

## ✍️ Authors <a name = "authors"></a>

- [Tim Sudin PPAPP Jakarta Timur]

## Troubleshooting

1. Jika muncul error saat akses website:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

2. Jika ada masalah permission storage:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

3. Jika database migration error:
```bash
php artisan migrate:fresh
```
