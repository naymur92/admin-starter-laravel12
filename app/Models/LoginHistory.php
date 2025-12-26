<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $table = 'login_history';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'location',
        'is_successful',
        'login_method',
        'login_at',
        'logout_at',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include successful logins.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope a query to only include failed logins.
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Scope a query to filter by login method.
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('login_method', $method);
    }

    /**
     * Get formatted login method label.
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->login_method) {
            'web' => 'Web',
            'api' => 'API',
            'oauth' => 'OAuth Token',
            'oauth_refresh' => 'OAuth Refresh',
            default => ucfirst($this->login_method),
        };
    }
}
