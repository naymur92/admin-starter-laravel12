# API Documentation

This document provides detailed information about the Admin Template API endpoints, specifically focusing on authentication using OAuth2.

## Base URL

```
http://your-domain.com/api
```

## Authentication

The API uses OAuth2 authentication with Laravel Passport. All authenticated endpoints require a valid access token.

### Header Format

For all authenticated API requests, include the following header:

```http
Authorization: Bearer {access_token}
Accept: application/json
Content-Type: application/json
```

**Example:**

```http
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
Accept: application/json
Content-Type: application/json
```

---

## Authentication Endpoints

### 1. Generate Token (Login)

Authenticates a user and generates an access token.

**Endpoint:** `POST /api/auth/token`

**Request Headers:**

```http
Accept: application/json
Content-Type: application/json
X-Client-Id: {{ClientID}}
X-Client-Secret: {{ClientSecret}}
```

**Header Parameters:**

| Header          | Required | Description                                  |
| --------------- | -------- | -------------------------------------------- |
| Accept          | Yes      | Must be "application/json"                   |
| Content-Type    | Yes      | Must be "application/json"                   |
| X-Client-Id     | Yes      | OAuth2 client ID (from passport:install)     |
| X-Client-Secret | Yes      | OAuth2 client secret (from passport:install) |

**Request Body:**

```json
{
    "email": "api@example.com",
    "password": "111111"
}
```

**Parameters:**

| Parameter | Type   | Required | Description          |
| --------- | ------ | -------- | -------------------- |
| email     | string | Yes      | User's email address |
| password  | string | Yes      | User's password      |

**Important Notes:**

-   Only users with `type = 4` (API Users) can authenticate via this endpoint
-   The user must have `is_active = 1` status
-   Other user types (Admin, Staff, etc.) cannot login through the API

**Success Response (200 OK):**

```json
{
    "flag": true,
    "msg": "Success",
    "token_type": "Bearer",
    "expires_in": 10800,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIwMTliNTY2NS0wYmMxLTcxNTgtYWNkMC1mNDljOWYyZGQwMTIiLCJqdGkiOiIwN2ExNjdmYzM0Yjc1MTQwMWUyMjI5NTZmODVlMDE0MTk4MGE0Mjc5ZTQ2ZTkxMjUxNjhmYTc3MDZmNzliNDM2MTc0ZWJhMDRiMGNhYjM4NiIsImlhdCI6MTc2NjczODcwMi41NTEzNjIsIm5iZiI6MTc2NjczODcwMi41NTEzNjQsImV4cCI6MTc2Njc0OTUwMi41NDE0LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0...",
    "refresh_token": "def50200cc18bf006db51be35908a60f349fa662d5a7b39c190858ae15269b6b55fcac7642b9df5724f5ec6bcee7124fee93968da9786b845946e5e2d174bc6d13f4f6ce45ba3b3510d21e43f3615608ee65b73ba5cc25964237d45879cf386d1a9ff3527e4b06226199092a62210b11a290256c93f25f7d6e396658c12473167d56b6406ad43ab400e48aefba2a950027aec07e8600d5c7446a04f360e6438ca81def3c76e63cf004aa51763...",
    "data": [],
    "response_code": 200
}
```

**Response Fields:**

| Field         | Type    | Description                                  |
| ------------- | ------- | -------------------------------------------- |
| flag          | boolean | Success status (true/false)                  |
| msg           | string  | Response message                             |
| token_type    | string  | Always "Bearer"                              |
| expires_in    | integer | Token expiration time in seconds (3 hours)   |
| access_token  | string  | JWT access token for API authentication      |
| refresh_token | string  | Token used to refresh the access token       |
| data          | array   | Additional data (empty for token generation) |
| response_code | integer | HTTP response code                           |

**Error Response (401 Unauthorized):**

```json
{
    "flag": false,
    "msg": "Invalid credentials",
    "data": [],
    "response_code": 401
}
```

**Common Error Scenarios:**

-   Invalid email or password
-   User is not an API user (type ≠ 4)
-   User account is inactive (is_active = 0)
-   User account does not exist

**Error Response (422 Validation Error):**

```json
{
    "flag": false,
    "msg": "Email is required. | Password is required.",
    "errors": {
        "email": ["Email is required."],
        "password": ["Password is required."]
    },
    "data": null,
    "response_code": 422
}
```

**cURL Example:**

```bash
curl -X POST http://your-domain.com/api/auth/token \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Client-Id: 2" \
  -H "X-Client-Secret: your-client-secret-here" \
  -d '{
    "email": "api@example.com",
    "password": "111111"
  }'
```

**JavaScript Example:**

```javascript
fetch("http://your-domain.com/api/auth/token", {
    method: "POST",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-Client-Id": "2",
        "X-Client-Secret": "your-client-secret-here",
    },
    body: JSON.stringify({
        email: "api@example.com",
        password: "111111",
    }),
})
    .then((response) => response.json())
    .then((data) => {
        if (data.flag) {
            console.log("Success:", data.msg);
            console.log("Access Token:", data.access_token);
            console.log("Refresh Token:", data.refresh_token);
            console.log("Expires in:", data.expires_in, "seconds");
        } else {
            console.error("Error:", data.msg);
        }
    })
    .catch((error) => console.error("Error:", error));
```

---

### 2. Refresh Token

Generates a new access token using a refresh token.

**Endpoint:** `POST /api/auth/token`

**Request Headers:**

```http
Accept: application/json
Content-Type: application/json
X-Client-Id: {{ClientID}}
X-Client-Secret: {{ClientSecret}}
```

**Header Parameters:**

| Header          | Required | Description                |
| --------------- | -------- | -------------------------- |
| Accept          | Yes      | Must be "application/json" |
| Content-Type    | Yes      | Must be "application/json" |
| X-Client-Id     | Yes      | OAuth2 client ID           |
| X-Client-Secret | Yes      | OAuth2 client secret       |

**Request Body:**

```json
{
    "refresh_token": "def50200cc18bf006db51be35908a60f349fa662d5a7b39c..."
}
```

**Parameters:**

| Parameter     | Type   | Required | Description                               |
| ------------- | ------ | -------- | ----------------------------------------- |
| refresh_token | string | Yes      | The refresh token from the login response |

**Success Response (200 OK):**

```json
{
    "flag": true,
    "msg": "Token refreshed successfully",
    "token_type": "Bearer",
    "expires_in": 10800,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.NEW_ACCESS_TOKEN...",
    "refresh_token": "def50200NEW_REFRESH_TOKEN...",
    "data": [],
    "response_code": 200
}
```

**Response Fields:**

| Field         | Type    | Description                               |
| ------------- | ------- | ----------------------------------------- |
| flag          | boolean | Success status (true/false)               |
| msg           | string  | Response message                          |
| token_type    | string  | Always "Bearer"                           |
| expires_in    | integer | New token expiration time in seconds      |
| access_token  | string  | New JWT access token                      |
| refresh_token | string  | New refresh token                         |
| data          | array   | Additional data (empty for token refresh) |
| response_code | integer | HTTP response code                        |

**Error Response (401 Unauthorized):**

```json
{
    "flag": false,
    "msg": "Invalid or expired refresh token",
    "data": [],
    "response_code": 401
}
```

**cURL Example:**

```bash
curl -X POST http://your-domain.com/api/auth/token \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Client-Id: 2" \
  -H "X-Client-Secret: your-client-secret-here" \
  -d '{
    "refresh_token": "def50200cc18bf006db51be35908a60f349fa662d5a7b39c..."
  }'
```

**JavaScript Example:**

```javascript
fetch("http://your-domain.com/api/auth/token", {
    method: "POST",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-Client-Id": "2",
        "X-Client-Secret": "your-client-secret-here",
    },
    body: JSON.stringify({
        refresh_token: "def50200cc18bf006db51be35908a60f349fa662d5a7b39c...",
    }),
})
    .then((response) => response.json())
    .then((data) => {
        if (data.flag) {
            console.log("Success:", data.msg);
            console.log("New Access Token:", data.access_token);
            console.log("New Refresh Token:", data.refresh_token);
            // Store new tokens
            localStorage.setItem("access_token", data.access_token);
            localStorage.setItem("refresh_token", data.refresh_token);
        } else {
            console.error("Error:", data.msg);
        }
    })
    .catch((error) => console.error("Error:", error));
```

---

## Using the Access Token

Once you have an access token, include it in the Authorization header for all protected API requests.

**Example Request with Token:**

```bash
curl -X GET http://your-domain.com/api/user \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..." \
  -H "Accept: application/json"
```

**JavaScript Example:**

```javascript
const accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...";

fetch("http://your-domain.com/api/user", {
    method: "GET",
    headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json",
    },
})
    .then((response) => response.json())
    .then((data) => console.log("User Data:", data))
    .catch((error) => console.error("Error:", error));
```

---

## Token Management Best Practices

### 1. Store Tokens Securely

-   **Web Apps:** Use secure HTTP-only cookies or encrypted localStorage
-   **Mobile Apps:** Use secure storage mechanisms (Keychain, KeyStore)
-   **Never** expose tokens in URLs or logs

### 2. Handle Token Expiration

```javascript
// Check if token is expired before making requests
function isTokenExpired(expiresAt) {
    return Date.now() >= expiresAt * 1000;
}

// Auto-refresh token when needed
async function makeAuthenticatedRequest(url, options = {}) {
    let accessToken = localStorage.getItem("access_token");
    const expiresAt = localStorage.getItem("expires_at");

    if (isTokenExpired(expiresAt)) {
        // Refresh the token
        const refreshToken = localStorage.getItem("refresh_token");
        const newTokens = await refreshAccessToken(refreshToken);
        accessToken = newTokens.access_token;
    }

    return fetch(url, {
        ...options,
        headers: {
            ...options.headers,
            Authorization: `Bearer ${accessToken}`,
            Accept: "application/json",
        },
    });
}
```

### 3. Handle 401 Responses

```javascript
fetch("http://your-domain.com/api/user", {
    headers: {
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json",
    },
})
    .then((response) => {
        if (response.status === 401) {
            // Token expired or invalid - refresh or redirect to login
            return refreshTokenAndRetry();
        }
        return response.json();
    })
    .then((data) => console.log(data));
```

### 4. Logout / Revoke Token

When implementing logout, revoke the access token on the server side (if you have a logout endpoint).

---

## Response Format

All API responses follow a consistent format using the CustomResponseTrait:

**Success Response:**

```json
{
    "flag": true,
    "msg": "Success message",
    "data": {},
    "response_code": 200
}
```

**Error Response:**

```json
{
    "flag": false,
    "msg": "Error message",
    "data": {},
    "response_code": 400/401/422/500
}
```

---

## Error Codes

| HTTP Status | Error Code       | Description                                 |
| ----------- | ---------------- | ------------------------------------------- |
| 400         | invalid_request  | The request is missing a required parameter |
| 401         | invalid_client   | Client authentication failed                |
| 401         | invalid_grant    | Invalid credentials or refresh token        |
| 401         | unauthorized     | Token is missing, invalid, or expired       |
| 403         | access_denied    | User doesn't have permission                |
| 422         | validation_error | Request validation failed                   |
| 500         | server_error     | Internal server error                       |

---

## Rate Limiting

The API may implement rate limiting to prevent abuse. If you exceed the rate limit, you'll receive a `429 Too Many Requests` response.

**Response Headers:**

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 0
Retry-After: 60
```

---

## Laravel Passport Setup

This application uses Laravel Passport for OAuth2 authentication. Here's how to set it up:

### Installation & Key Generation

**1. Install Passport via Composer:**

Already included in `composer.json`. If needed:

```bash
composer require laravel/passport
```

**2. Run Migrations:**

Passport requires specific database tables:

```bash
php artisan migrate
```

This creates tables:

-   `oauth_auth_codes`
-   `oauth_access_tokens`
-   `oauth_refresh_tokens`
-   `oauth_clients`
-   `oauth_personal_access_clients`

**3. Generate Encryption Keys:**

Generate the encryption keys needed to generate secure access tokens:

```bash
php artisan passport:install
```

**Output:**

```
Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client secret: xxxxxxxxxxxxxxxxxxxxx

Password grant client created successfully.
Client ID: 2
Client secret: xxxxxxxxxxxxxxxxxxxxx
```

**Important:** Save the **Password grant client** credentials (Client ID: 2). You'll need these for the `X-Client-Id` and `X-Client-Secret` headers.

**4. Key Storage:**

The command creates two files in `storage/`:

-   `oauth-private.key` - Private key for signing tokens
-   `oauth-public.key` - Public key for verifying tokens

**Security Note:** Keep these keys secure and never commit them to version control.

### Configuration

**1. Update AuthServiceProvider:**

The application is already configured in `app/Providers/AuthServiceProvider.php`:

```php
use Laravel\Passport\Passport;

public function boot(): void
{
    Passport::tokensExpireIn(now()->addHours(3));
    Passport::refreshTokensExpireIn(now()->addDays(30));
    Passport::personalAccessTokensExpireIn(now()->addMonths(6));
}
```

**Token Expiration:**

-   Access tokens: 3 hours (10800 seconds)
-   Refresh tokens: 30 days
-   Personal access tokens: 6 months

**2. Update config/auth.php:**

Already configured:

```php
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

### Custom OAuth Route

The application uses a custom OAuth endpoint:

```
POST /oauth-admin-app/token
```

Configured in `routes/web.php`:

```php
Route::post('oauth-admin-app/token', function (Request $request) {
    // Custom token endpoint with middleware
});
```

### Regenerating Keys

If you need to regenerate keys:

```bash
# Delete old keys
rm storage/oauth-*.key

# Regenerate
php artisan passport:install --force
```

**Warning:** Regenerating keys will invalidate all existing tokens.

### Checking Passport Status

Verify Passport clients:

```bash
php artisan passport:client --list
```

Or check in database:

```sql
SELECT * FROM oauth_clients;
```

### Troubleshooting

**Issue: "Client authentication failed"**

-   Verify `X-Client-Id` and `X-Client-Secret` headers match the password grant client
-   Check `oauth_clients` table for correct credentials

**Issue: "Key path does not exist"**

-   Run `php artisan passport:install`
-   Check storage permissions: `chmod -R 775 storage`

**Issue: "Invalid token signature"**

-   Keys might be corrupted - regenerate with `passport:install --force`
-   Check `oauth-public.key` and `oauth-private.key` exist in `storage/`

**Issue: "Token expired"**

-   Use the refresh token endpoint to get a new access token
-   Access tokens expire after 3 hours

---

## Support

For additional information:

-   Check the [Installation Guide](INSTALLATION.md)
-   Review the [Environment Setup Guide](ENVIRONMENT_SETUP.md)
-   Consult Laravel Passport documentation: https://laravel.com/docs/passport
