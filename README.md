# Admin Template

A secure Laravel application with OAuth2 authentication, role-based access control, file encryption, activity logging, login tracking, and configurable settings management.

## Features

-   **OAuth2 Authentication** - Laravel Passport for secure token-based authentication
-   **Role-Based Access Control (RBAC)** - Using Spatie Laravel Permission package
-   **File Encryption** - Secure file storage with AES-256 encryption
-   **Activity Logging** - Comprehensive audit trail of user actions
-   **Login Tracking** - Track user login history with IP and device information
-   **Settings Management** - Configurable application settings with caching and backup
-   **Notifications** - Flash notifications for user feedback
-   **Admin Panel UI Auth** - Laravel UI-powered session login/logout for admin panel

## Technologies

-   Laravel 12.x
-   PHP 8.3+
-   MySQL/MariaDB
-   Laravel Passport (OAuth2)
-   Spatie Laravel Permission
-   Defuse PHP Encryption
-   Mobile Detect
-   Flasher Notifications

## Quick Start

See the [Installation Guide](docs/INSTALLATION.md) for detailed setup instructions.

See the [Environment Setup Guide](docs/ENVIRONMENT_SETUP.md) for environment configuration.

See the [API Documentation](docs/API_DOCUMENTATION.md) for API usage.

## Documentation

-   [Installation Guide](docs/INSTALLATION.md)
-   [Environment Setup Guide](docs/ENVIRONMENT_SETUP.md)
-   [API Documentation](docs/API_DOCUMENTATION.md)
-   [Activity Logging](docs/ACTIVITY_LOGGING.md)
-   [Settings, Backup & Cache](docs/SETTINGS_BACKUP_CACHE.md)

## Security Features

### Authentication & Authorization

-   OAuth2 password grant authentication
-   Token-based API authentication
-   Role and permission-based access control

### Data Protection

-   AES-256 file encryption
-   Secure file storage and retrieval
-   Secure password hashing

### Monitoring & Auditing

-   Comprehensive activity logging
-   Login history tracking
-   IP and device detection

### Access Control

-   Role-based permissions
-   Route-level access control
-   Middleware protection

## Requirements

-   PHP 8.2 or higher
-   Composer
-   MySQL 5.7+ or MariaDB 10.3+
-   Node.js & NPM (for frontend assets)
-   Apache/Nginx web server

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
