# Activity & Audit Features Documentation

## Overview

This implementation adds comprehensive Activity & Audit logging capabilities to your Laravel application with three main components:

1. **Activity Logs** - Track user actions and system events
2. **Login History** - Monitor user authentication attempts and sessions
3. **System Logs Viewer** - Web interface to view Laravel log files

## Features Implemented

### 1. Activity Logs (`/admin/activity-logs`)

Tracks all significant user actions within the system.

**Features:**

-   Records user actions with detailed metadata
-   Captures IP address, user agent, and request details
-   Supports polymorphic relationships for subjects and causers
-   Advanced filtering by log name, event type, date range, and search
-   Automatic sanitization of sensitive data (passwords, tokens, etc.)
-   Cleanup functionality to remove old logs

**Database Schema:**

-   `log_name` - Category of the log (web, api, model_event, etc.)
-   `description` - Human-readable description of the action
-   `subject` - The model that was acted upon (polymorphic)
-   `causer` - The user who performed the action (polymorphic)
-   `properties` - JSON field for additional data
-   `event` - Type of event (POST, PUT, DELETE, created, updated, etc.)
-   `ip_address` - User's IP address
-   `user_agent` - Browser/client information

### 2. Login History (`/admin/login-history`)

Monitors all login attempts and tracks user sessions.

**Features:**

-   Records every login attempt (successful and failed)
-   Tracks device, browser, and platform information
-   Monitors session duration (login to logout)
-   Supports multiple authentication methods:
    -   **Web** - Traditional web browser logins
    -   **API** - Direct API authentication
    -   **OAuth** - OAuth token generation (password grant)
    -   **OAuth Refresh** - OAuth token refresh operations
-   Automatic tracking for web logins and OAuth API requests
-   Statistics dashboard showing total logins, success/failure rates
-   User-specific login history view
-   Advanced filtering capabilities

**Database Schema:**

-   `user_id` - The authenticated user
-   `ip_address` - Login IP address
-   `user_agent` - Full user agent string
-   `device` - Device type (Desktop, Mobile, Tablet)
-   `browser` - Browser name and version
-   `platform` - Operating system
-   `location` - Geographical location (optional)
-   `is_successful` - Login success/failure status
-   `login_method` - Authentication method (web/api/oauth/oauth_refresh)
-   `login_at` - Login timestamp
-   `logout_at` - Logout timestamp (nullable)

### 3. System Logs Viewer (`/admin/system-logs`)

Web-based interface to view Laravel log files.

**Features:**

-   Lists all log files in storage/logs directory
-   View log contents with syntax highlighting
-   Search within log files
-   Configurable number of lines to display (50-1000)
-   Download log files
-   Delete old log files (protection for current logs)
-   Color-coded log levels (error, warning, info, debug)
-   File size and modification time display

## Usage

### Automatic Activity Logging

The `LogActivity` middleware automatically logs non-GET requests for authenticated users:

```php
// Middleware is already registered and active on all web routes
// It automatically logs actions like:
// - User creation/updates
// - Role/permission changes
// - OAuth client management
// - Profile updates
```

### Manual Activity Logging

Use the helper function or service class:

```php
// Using helper function
logActivity('Custom action performed', $model, ['key' => 'value'], 'custom');

// Using service class
use App\Services\ActivityLogger;

ActivityLogger::log(
    description: 'User exported data',
    subject: $user,
    properties: ['format' => 'csv', 'rows' => 100],
    event: 'export',
    logName: 'data_export'
);

// Log model events
ActivityLogger::logModelEvent('created', $user, ['admin_created' => true]);
```

### Login Tracking

Login tracking is automatically integrated into the authentication flow:

```php
// Automatic tracking on successful login (already implemented)
// Web logins - In LoginController@authenticated
// OAuth logins - In API\AuthController@issueToken
// OAuth refresh - In API\AuthController@refresh

// Manual tracking
use App\Services\LoginTracker;

LoginTracker::track($user, true, 'web'); // Successful web login
LoginTracker::track($user, false, 'api'); // Failed API login attempt
LoginTracker::track($user, true, 'oauth'); // Successful OAuth token generation
LoginTracker::track($user, true, 'oauth_refresh'); // Successful token refresh
LoginTracker::trackLogout($user); // Track logout
```

### OAuth API Login Tracking

OAuth token operations are automatically tracked:

**Token Generation (`POST /api/auth/token`):**

```php
// Automatically tracks:
// - Successful OAuth logins with method 'oauth'
// - Failed login attempts with user email lookup
// - Device, browser, IP, and user agent information
```

**Token Refresh (`POST /api/auth/token/refresh`):**

```php
// Automatically tracks:
// - Successful token refresh with method 'oauth_refresh'
// - User identified from JWT access token payload
// - All login metadata (IP, device, browser, etc.)
```

### User Relationships

Access logs through User model:

```php
$user = User::find(1);

// Get all login history
$logins = $user->loginHistory;

// Get recent successful logins
$recentLogins = $user->loginHistory()
    ->successful()
    ->latest('login_at')
    ->take(10)
    ->get();

// Get activity logs
$activities = $user->activityLogs;
```

## Web Interface Routes

### Admin Routes (Require Authentication + Admin Middleware + Permissions)

**Activity Logs** (requires `activity-log-*` permissions):

-   `GET /admin/activity-logs` - List all activity logs (permission: `activity-log-list`)
-   `GET /admin/activity-logs/{id}` - View activity log details (permission: `activity-log-view`)
-   `POST /admin/activity-logs/cleanup` - Clean up old logs (permission: `activity-log-delete`)

**Login History** (requires `login-history-*` permissions):

-   `GET /admin/login-history` - List all login history with statistics (permission: `login-history-list`)
-   `GET /admin/login-history/{id}` - View login details (permission: `login-history-view`)

**System Logs** (requires `system-log-*` permissions):

-   `GET /admin/system-logs` - List all system log files (permission: `system-log-list`)
-   `GET /admin/system-logs/{filename}` - View log file contents (permission: `system-log-view`)
-   `GET /admin/system-logs/{filename}/download` - Download log file (permission: `system-log-download`)
-   `DELETE /admin/system-logs/{filename}` - Delete log file (permission: `system-log-delete`)

### User Routes (No Special Permissions Required)

-   `GET /admin/user-profile/login-history` - View own login history

### API Routes (OAuth Token Operations)

-   `POST /api/auth/token` - Generate OAuth access token (tracks as 'oauth')
-   `POST /api/auth/token/refresh` - Refresh OAuth token (tracks as 'oauth_refresh')

## Models

### ActivityLog Model

```php
// Scopes
ActivityLog::inLog('web')->get();
ActivityLog::causedBy($user)->get();
ActivityLog::forSubject($model)->get();

// Relationships
$log->causer; // User who performed action
$log->subject; // Model that was acted upon
```

### LoginHistory Model

```php
// Scopes
LoginHistory::successful()->get();
LoginHistory::failed()->get();
LoginHistory::byMethod('web')->get();
LoginHistory::byMethod('oauth')->get();
LoginHistory::byMethod('oauth_refresh')->get();

// Relationships
$login->user; // The authenticated user

// Attributes
$login->method_label; // Formatted label (Web, API, OAuth Token, OAuth Refresh)
```

## Middleware

### LogActivity Middleware

Automatically logs user activities for non-GET requests.

**Skipped Routes:**

-   logout
-   login
-   activity-logs
-   login-history
-   system-logs

**Sanitized Fields:**

-   password
-   password_confirmation
-   secret
-   token
-   api_key

## Services

### ActivityLogger Service

Static methods for logging activities:

-   `log()` - General purpose logging
-   `logUserAction()` - Log user-specific actions
-   `logModelEvent()` - Log model events (created, updated, deleted)

### LoginTracker Service

Static methods for login tracking:

-   `track()` - Track login attempt
-   `trackLogout()` - Track logout
-   `getDevice()` - Determine device type
-   `getLocation()` - Get geographical location (placeholder)

## Dependencies

### Installed Packages

-   `jenssegers/agent` (v2.6.4) - User agent parsing for device/browser detection

## Security Considerations

1. **Sensitive Data**: Passwords, tokens, and secrets are automatically redacted
2. **Access Control**: All audit routes require authentication, admin middleware, and specific permissions
3. **Permission-Based Access**:
    - Activity logs require: `activity-log-list`, `activity-log-view`, `activity-log-delete`
    - Login history requires: `login-history-list`, `login-history-view`
    - System logs require: `system-log-list`, `system-log-view`, `system-log-download`, `system-log-delete`
4. **Log Protection**: Current day's log file cannot be deleted
5. **Input Sanitization**: All logged data is sanitized before storage
6. **OAuth Token Security**: Refresh tokens are encrypted; user identification uses JWT payload

## Performance Tips

1. **Pagination**: All list views use pagination (50 items per page)
2. **Indexes**: Database indexes on commonly queried fields
3. **Cleanup**: Regularly clean old activity logs using the cleanup feature
4. **Filtering**: Use filters to narrow down results instead of loading all data

## Customization

### Add Custom Activity Descriptions

Edit `LogActivity` middleware's `getDescription()` method:

```php
protected function getDescription(Request $request): ?string
{
    $descriptions = [
        'your.route.name' => 'Your custom description',
        // ...
    ];
}
```

### Extend Login Tracking

Add geolocation service in `LoginTracker`:

```php
private static function getLocation(string $ip): ?string
{
    // Integrate with ipapi.co, geoip2, or similar
    // Example:
    // $response = Http::get("https://ipapi.co/{$ip}/json/");
    // return $response['city'] . ', ' . $response['country_name'];
}
```

## Navigation

A new "Activity & Audit" section has been added to the sidebar with permission-based access:

-   **Activity Logs** (visible with `activity-log-list` permission)
-   **Login History** (visible with `login-history-list` permission)
-   **System Logs** (visible with `system-log-list` permission)

The entire menu section is visible if the user has at least one of the above permissions.

Users can also view their own login history from their profile page (no special permission required).

## Best Practices

1. **Regular Cleanup**: Schedule regular cleanup of old activity logs
2. **Monitor Failed Logins**: Check for unusual patterns in failed login attempts
3. **Review System Logs**: Regularly check system logs for errors
4. **Data Retention**: Define a retention policy for logs
5. **Performance**: Monitor database size and query performance as logs grow

## Testing

To test the features:

1. **Web Login**: Login to the application (generates login history with method 'web')
2. **User Actions**: Perform various actions (creates activity logs)
3. **OAuth Token**: Request a token via API (generates login history with method 'oauth')
4. **Token Refresh**: Refresh an OAuth token (generates login history with method 'oauth_refresh')
5. **View Logs**:
    - Navigate to /admin/activity-logs to view activities
    - Navigate to /admin/login-history to view all login records
    - Navigate to /admin/system-logs to view Laravel logs
    - Check your profile page for personal login history
6. **Verify Permissions**: Test that routes require appropriate permissions

## Troubleshooting

**Issue: Activities not being logged**

-   Ensure middleware is properly registered in bootstrap/app.php
-   Check that routes are using 'web' middleware group
-   Verify user is authenticated

**Issue: Login history not recording**

-   Check LoginController integration for web logins
-   Verify API\AuthController is tracking OAuth operations
-   Verify jenssegers/agent package is installed
-   Ensure database migration ran successfully

**Issue: OAuth refresh not being tracked**

-   Check Laravel logs for "User found from access token" message
-   Verify JWT token is being decoded correctly
-   Ensure user exists in database
-   Check that the access token contains a valid `sub` claim

**Issue: System logs not showing**

-   Verify storage/logs directory permissions
-   Check that Laravel is configured to write logs
-   Ensure log files have .log extension

**Issue: Permission denied errors**

-   Verify user has appropriate permissions assigned
-   Check that permissions were seeded: `php artisan db:seed --class=PermissionTableSeeder`
-   Run migration: `php artisan migrate` (to assign permissions to Super Admin)
-   Verify role permissions: Check that user's role has the required permissions
