<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and non-GET requests
        if (auth()->check() && !$request->isMethod('GET')) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Log the activity.
     */
    protected function logActivity(Request $request, Response $response)
    {
        // Skip logging for certain routes
        $skipRoutes = ['logout', 'login', 'activity-logs', 'login-history', 'system-logs'];

        foreach ($skipRoutes as $route) {
            if (str_contains($request->path(), $route)) {
                return;
            }
        }

        $description = $this->getDescription($request);

        if ($description) {
            ActivityLog::create([
                'log_name' => 'web',
                'description' => $description,
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => 'App\Models\User',
                'causer_id' => auth()->id(),
                'properties' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'route' => $request->route()?->getName(),
                    'status_code' => $response->getStatusCode(),
                    'input' => $this->sanitizeInput($request->except(['_token', 'password', 'password_confirmation'])),
                ],
                'event' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    /**
     * Get activity description based on route.
     */
    protected function getDescription(Request $request): ?string
    {
        $routeName = $request->route()?->getName();
        $method = $request->method();

        $descriptions = [
            'users.store' => 'Created a new user',
            'users.update' => 'Updated user information',
            'users.change-status' => 'Changed user status',
            'users.update-password' => 'Changed user password',
            'roles.store' => 'Created a new role',
            'roles.update' => 'Updated role information',
            'roles.destroy' => 'Deleted a role',
            'permissions.store' => 'Created a new permission',
            'permissions.destroy' => 'Deleted a permission',
            'oauth-clients.store' => 'Created a new OAuth client',
            'oauth-clients.update' => 'Updated OAuth client',
            'oauth-clients.destroy' => 'Deleted OAuth client',
            'oauth-clients.regenerate-secret' => 'Regenerated OAuth client secret',
            'user-profile.update' => 'Updated own profile',
            'user-profile.update-password' => 'Changed own password',
            'user-profile.change-profile-picture' => 'Changed profile picture',
        ];

        return $descriptions[$routeName] ?? null;
    }

    /**
     * Sanitize input to remove sensitive data.
     */
    protected function sanitizeInput(array $input): array
    {
        $sensitive = ['password', 'password_confirmation', 'secret', 'token', 'api_key'];

        foreach ($sensitive as $key) {
            if (isset($input[$key])) {
                $input[$key] = '[REDACTED]';
            }
        }

        return $input;
    }
}
