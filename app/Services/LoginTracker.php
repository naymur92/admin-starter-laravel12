<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Jenssegers\Agent\Agent;

class LoginTracker
{
    /**
     * Track a login attempt.
     */
    public static function track(
        User $user,
        bool $isSuccessful = true,
        string $method = 'web'
    ): LoginHistory {
        $agent = new Agent();

        return LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device' => self::getDevice($agent),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'location' => self::getLocation(request()->ip()),
            'is_successful' => $isSuccessful,
            'login_method' => $method,
            'login_at' => now(),
        ]);
    }

    /**
     * Track a logout.
     */
    public static function trackLogout(User $user): void
    {
        $latestLogin = LoginHistory::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();

        if ($latestLogin) {
            $latestLogin->update(['logout_at' => now()]);
        }
    }

    /**
     * Get device type.
     */
    private static function getDevice(Agent $agent): string
    {
        if ($agent->isDesktop()) {
            return 'Desktop';
        } elseif ($agent->isTablet()) {
            return 'Tablet';
        } elseif ($agent->isMobile()) {
            return 'Mobile';
        } elseif ($agent->isRobot()) {
            return 'Robot';
        }

        return 'Unknown';
    }

    /**
     * Get approximate location from IP (simplified version).
     * For production, consider using a service like ipapi.co or geoip2
     */
    private static function getLocation(string $ip): ?string
    {
        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return 'Local';
        }

        // In a real application, you would use a geolocation service
        // For now, just return null
        return null;
    }
}
