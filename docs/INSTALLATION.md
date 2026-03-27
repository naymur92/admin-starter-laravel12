# Installation Guide

This guide will walk you through the installation process for the Admin Template application.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

-   **PHP 8.2 or higher**
    -   Required extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath
    -   disable_functions should allow exec (for DB backups)
-   **Composer** (latest version)
-   **MySQL 5.7+** or **MariaDB 10.3+**
-   **Node.js & NPM** (for frontend assets)
-   **Apache or Nginx** web server
-   **Git** (optional, for cloning the repository)

## Installation Steps

### 1. Clone or Download the Project

```bash
# If using Git
git clone git@github.com:naymur92/admin-starter-laravel12.git admin_template
cd admin_template

# Or extract the downloaded ZIP file and navigate to the project directory
```

### 2. Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages including:

-   Laravel Framework
-   Laravel UI
-   Laravel Passport
-   Spatie Laravel Permission
-   And other dependencies

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file:

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

See the [Environment Setup Guide](ENVIRONMENT_SETUP.md) for detailed configuration options.

### 5. Generate Application Key

```bash
php artisan key:generate
```

This will generate a unique application key and update your `.env` file.

### 6. Database Setup

Create a new database in MySQL/MariaDB:

```sql
CREATE DATABASE admin_template CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admin_template
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 7. Run Database Migrations

```bash
php artisan migrate
```

This will create all necessary tables:

-   users
-   roles and permissions
-   oauth\_\* (Passport tables)
-   files
-   activity_logs
-   login_histories
-   settings
-   sessions
-   cache
-   jobs

### 8. Seed the Database (Optional)

If you want to populate the database with sample data:

```bash
php artisan db:seed
```

### 9. Generate Laravel Passport Keys

```bash
php artisan passport:keys
```

This command will:

-   Create encryption keys for generating secure access tokens

### 10. Create Storage Symbolic Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

### 12. Set Directory Permissions

Ensure the following directories are writable by the web server:

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows - Set appropriate permissions in folder properties
```

### 13. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 14. Configure Web Server

#### Apache

Ensure `mod_rewrite` is enabled and point your virtual host to the `public` directory.

Example virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName admin-template.local
    DocumentRoot "C:/laragon/www/admin_template/public"

    <Directory "C:/laragon/www/admin_template/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

Example Nginx configuration:

```nginx
server {
    listen 80;
    server_name admin-template.local;
    root /var/www/admin_template/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 15. Clear and Cache Configuration

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Post-Installation

### Create Roles And Permissions

You can create roles and permissions, set permissions from admin panel.

### Create Admin User

You can create an admin user manually or through the database seeder.

### Access the Application

Open your browser and navigate to:

-   http://admin-template.local (or your configured domain)
-   http://localhost/admin_template/public (if using default setup)

### API Testing

Use tools like Postman or cURL to test the API endpoints:

```bash
# Test API
curl --location 'http://admin-template.local/api/user' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'Authorization: ••••••'
```

## Troubleshooting

### Common Issues

\*\*Issue: generateAssetPath issue.
// config/filesystems.php

'assets' => [
'driver' => 'local',
'root' => public_path('assets'),
],

**Issue: 500 Internal Server Error**

-   Check storage and bootstrap/cache permissions
-   Ensure `.env` file exists and is properly configured
-   Check Laravel logs in `storage/logs/laravel.log`

**Issue: Database Connection Failed**

-   Verify database credentials in `.env`
-   Ensure MySQL/MariaDB service is running
-   Check if database exists

**Issue: Passport Keys Missing**

-   Run `php artisan passport:keys` again
-   Check if `oauth-private.key` and `oauth-public.key` exist in `storage/`

**Issue: File Upload Errors**

-   Check directory permissions
-   Verify `upload_max_filesize` in php.ini

### Getting Help

Check the logs:

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View web server logs
# Apache: /var/log/apache2/error.log
# Nginx: /var/log/nginx/error.log
```

## Next Steps

-   [Configure Environment Variables](ENVIRONMENT_SETUP.md)
-   [Read API Documentation](API_DOCUMENTATION.md)
-   [Set up Roles and Permissions](../README.md#security-features)
-   [Configure Activity Logging](ACTIVITY_LOGGING.md)
-   [Set up Application Settings](SETTINGS_BACKUP_CACHE.md)

## Production Deployment

For production deployment:

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Use a secure `APP_KEY`
3. Configure proper database credentials
4. Set up SSL/TLS certificates
5. Configure proper file permissions
6. Set up scheduled tasks for queue workers
7. Enable PHP OPcache
8. Set up monitoring and logging
9. Configure backups
10. Use a process manager like Supervisor for queue workers

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```
