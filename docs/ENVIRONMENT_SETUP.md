# Environment Setup Guide

This guide provides detailed information about configuring the `.env` file for the Admin Template application.

## Overview

The `.env` file contains all environment-specific configuration for your application. After installation, you should configure these settings according to your environment (development, staging, or production).

## Creating the Environment File

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

---

## Application Settings

### Basic Configuration

```env
APP_NAME="Admin Template"
APP_ENV=local
APP_KEY=base64:generated_key_here
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost
```

**Parameters:**

| Parameter    | Description          | Values                                          | Default          |
| ------------ | -------------------- | ----------------------------------------------- | ---------------- |
| APP_NAME     | Application name     | Any string                                      | "Laravel"        |
| APP_ENV      | Environment type     | local, development, staging, production         | local            |
| APP_KEY      | Encryption key       | Auto-generated (run `php artisan key:generate`) | -                |
| APP_DEBUG    | Enable debug mode    | true, false                                     | true             |
| APP_TIMEZONE | Application timezone | Valid timezone                                  | UTC              |
| APP_URL      | Application base URL | Full URL with protocol                          | http://localhost |

**Important Notes:**

-   **APP_ENV:** Set to `production` for live environments
-   **APP_DEBUG:** **Must be `false` in production** to prevent sensitive information leakage
-   **APP_KEY:** Generated automatically via `php artisan key:generate`
-   **APP_URL:** Should match your domain (e.g., https://yourdomain.com)

---

## Database Configuration

### MySQL/MariaDB Settings

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admin_template
DB_USERNAME=root
DB_PASSWORD=
```

**Parameters:**

| Parameter     | Description             | Example                              |
| ------------- | ----------------------- | ------------------------------------ |
| DB_CONNECTION | Database driver         | mysql, pgsql, sqlite, sqlsrv         |
| DB_HOST       | Database server address | 127.0.0.1, localhost, db.example.com |
| DB_PORT       | Database port           | 3306 (MySQL), 5432 (PostgreSQL)      |
| DB_DATABASE   | Database name           | admin_template                       |
| DB_USERNAME   | Database username       | root, admin                          |
| DB_PASSWORD   | Database password       | your_secure_password                 |

**Production Example:**

```env
DB_CONNECTION=mysql
DB_HOST=production-db.example.com
DB_PORT=3306
DB_DATABASE=admin_template_prod
DB_USERNAME=app_user
DB_PASSWORD=strong_secure_password_here
```

---

## Cache Configuration

```env
CACHE_STORE=database
CACHE_PREFIX=
```

**Available Drivers:**

-   `file` - File-based caching (default)
-   `database` - Database caching (recommended for this app)
-   `redis` - Redis caching (high performance)
-   `memcached` - Memcached caching
-   `array` - Array caching (testing only)

**For Redis:**

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

---

## Session Configuration

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

**Parameters:**

| Parameter        | Description                | Recommended           |
| ---------------- | -------------------------- | --------------------- |
| SESSION_DRIVER   | Storage driver             | database, redis, file |
| SESSION_LIFETIME | Session duration (minutes) | 120                   |
| SESSION_ENCRYPT  | Encrypt session data       | false                 |
| SESSION_PATH     | Cookie path                | /                     |
| SESSION_DOMAIN   | Cookie domain              | null or your domain   |

---

## Queue Configuration

```env
QUEUE_CONNECTION=database
```

**Available Drivers:**

-   `sync` - Synchronous (no queue, immediate execution)
-   `database` - Database queue (recommended)
-   `redis` - Redis queue (high performance)
-   `beanstalkd` - Beanstalkd queue
-   `sqs` - Amazon SQS

**For Production:**

```env
QUEUE_CONNECTION=redis  # or database
```

---

## Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Common SMTP Configurations:**

### Gmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Admin Template"
```

### SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
```

---

## Laravel Passport (OAuth2)

**Manual Configuration:**

-   Follow Laravel documentation if needed `https://laravel.com/docs/12.x/passport`

---

## Logging Configuration

```env
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
```

**Parameters:**

| Parameter   | Description             | Values                                                          |
| ----------- | ----------------------- | --------------------------------------------------------------- |
| LOG_CHANNEL | Primary logging channel | stack, single, daily, slack                                     |
| LOG_LEVEL   | Minimum log level       | debug, info, notice, warning, error, critical, alert, emergency |

**Production Settings:**

```env
LOG_CHANNEL=daily
LOG_LEVEL=error
```

---

## Filesystem Configuration

```env
FILESYSTEM_DISK=local
```

**Available Disks:**

-   `local` - Local storage (storage/app)
-   `public` - Public storage (storage/app/public)
-   `s3` - Amazon S3 (requires configuration)

**For S3:**

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

## Broadcasting Configuration

```env
BROADCAST_CONNECTION=log
```

**Available Drivers:**

-   `log` - Log broadcasts (development)
-   `pusher` - Pusher service
-   `ably` - Ably service
-   `redis` - Redis pub/sub
-   `null` - Disable broadcasting

---

## Vite Configuration

```env
VITE_APP_NAME="${APP_NAME}"
```

For asset compilation using Vite.

---

## Additional Security Settings

### Trusted Proxies

If your application is behind a load balancer or proxy:

```env
TRUSTED_PROXIES=*
# Or specify specific IPs
TRUSTED_PROXIES=192.168.1.1,10.0.0.1
```

### Force HTTPS (Production)

In `config/app.php`, you can force HTTPS:

```php
'force_https' => env('FORCE_HTTPS', false),
```

Then in `.env`:

```env
FORCE_HTTPS=true
```

---

## Environment-Specific Examples

### Development Environment

```env
APP_NAME="Admin Template Dev"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=admin_template_dev
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log

LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Production Environment

```env
APP_NAME="Admin Template"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://admintemplate.com

DB_CONNECTION=mysql
DB_HOST=production-db.internal
DB_DATABASE=admin_template_prod
DB_USERNAME=app_user
DB_PASSWORD=very_strong_password_here

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=redis.internal
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@admintemplate.com
MAIL_PASSWORD=app_specific_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@admintemplate.com"

LOG_CHANNEL=daily
LOG_LEVEL=warning

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=admintemplate-files

FORCE_HTTPS=true
```

---

## Security Best Practices

### 1. Never Commit `.env` to Version Control

The `.env` file should be in `.gitignore`:

```gitignore
.env
.env.backup
.env.production
```

### 2. Use Strong Credentials

-   Use strong, unique passwords for database access
-   Generate secure random strings for encryption keys
-   Use different credentials for each environment

### 3. Restrict File Permissions

```bash
# Linux/Mac
chmod 600 .env
chown www-data:www-data .env
```

### 4. Environment Variables for Sensitive Data

Never hard-code sensitive information. Always use environment variables:

```php
// Bad
$apiKey = 'hardcoded-api-key';

// Good
$apiKey = env('API_KEY');
```

### 5. Backup Your .env File

Keep a secure backup of your `.env` file, especially:

-   `APP_KEY`
-   Database credentials
-   API keys

---

## Verifying Configuration

After setting up your `.env` file:

### 1. Test Database Connection

```bash
php artisan migrate:status
```

### 2. Clear and Cache Configuration

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### 3. Check Environment

```bash
php artisan env
# Or
php artisan about
```

### 4. Test Mail Configuration

```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

---

## Troubleshooting

### Issue: Configuration Cached

If changes to `.env` don't take effect:

```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: Permission Denied

Check file permissions:

```bash
# Linux/Mac
ls -la .env
chmod 600 .env
```

### Issue: Database Connection Error

1. Verify database service is running
2. Check credentials in `.env`
3. Test connection:
    ```bash
    php artisan db:show
    ```

### Issue: Mail Not Sending

1. Verify SMTP credentials
2. Check firewall/port access
3. Test with `php artisan tinker`

---

## Next Steps

After configuring your environment:

1. [Complete Installation](INSTALLATION.md)
2. [Review API Documentation](API_DOCUMENTATION.md)
3. Set up your database and run migrations
4. Configure roles and permissions
5. Test the application

## References

-   [Laravel Configuration Documentation](https://laravel.com/docs/configuration)
-   [Laravel Environment Configuration](https://laravel.com/docs/configuration#environment-configuration)
-   [Laravel Passport Documentation](https://laravel.com/docs/passport)
