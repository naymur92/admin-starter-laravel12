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

## Admin Panel UI Authentication

This project includes a full admin panel protected by Laravel UI session-based authentication.

-   **UI Scaffolding**: The authentication UI is provided by Laravel UI and ships with a login view at [resources/views/auth/login.blade.php](resources/views/auth/login.blade.php).
-   **Routes**: Authentication routes are registered via Laravel UI in [routes/web.php](routes/web.php). Registration, password reset, and email confirmation are disabled for the admin panel.
-   **Admin Area**: All admin routes are prefixed with `/admin` and protected by `auth` and `admin` middleware in [routes/web.php](routes/web.php).
-   **Admin Gate**: The `admin` middleware checks `is_active` and `type` on the authenticated user. Only users with `is_active = 1` and `type ∈ {1, 2}` can access the admin panel. See [app/Http/Middleware/AdminMiddleware.php](app/Http/Middleware/AdminMiddleware.php).
-   **Guards**: The admin panel uses the default `web` session guard defined in [config/auth.php](config/auth.php). API routes use the `passport` driver under the `api` guard.

### How to Access the Admin Panel

1. Visit `/login` to authenticate.
2. After successful login, navigate to `/admin` for the dashboard and management pages.

### Creating Your First Admin User

If you don't have an admin user yet, create one quickly with Tinker:

```bash
php artisan tinker
```

Then run the following in the Tinker shell (adjust email/password as needed):

```php
use App\Models\User;
$user = new User();
$user->name = 'Admin User';
$user->email = 'admin@example.com';
$user->password = bcrypt('Admin@12345');
$user->is_active = 1;   // required by AdminMiddleware
$user->type = 1;        // 1 or 2 grants admin access
$user->save();
```

Optionally assign roles/permissions (Spatie) after creation:

```php
$user->assignRole('super-admin');
```

### Frontend Assets

Laravel UI is already required in [composer.json](composer.json). If you need to regenerate UI assets, you can run:

```bash
php artisan ui bootstrap --auth
npm install
npm run dev
```

Note: UI scaffolding is typically pre-installed in this template; the above is only needed if you customize or reset the auth views.

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
