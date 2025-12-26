<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Log an activity.
     */
    public static function log(
        string $description,
        ?Model $subject = null,
        ?Model $causer = null,
        ?array $properties = null,
        ?string $event = null,
        string $logName = 'default'
    ): ActivityLog {
        return ActivityLog::create([
            'log_name' => $logName,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'causer_type' => $causer ? get_class($causer) : (auth()->check() ? 'App\Models\User' : null),
            'causer_id' => $causer?->getKey() ?? auth()->id(),
            'properties' => $properties,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log a user action.
     */
    public static function logUserAction(string $description, ?array $properties = null): ActivityLog
    {
        return self::log(
            description: $description,
            causer: auth()->user(),
            properties: $properties,
            logName: 'user_action'
        );
    }

    /**
     * Log a model event (created, updated, deleted).
     */
    public static function logModelEvent(string $event, Model $model, ?array $properties = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = match ($event) {
            'created' => "Created {$modelName}",
            'updated' => "Updated {$modelName}",
            'deleted' => "Deleted {$modelName}",
            default => "{$event} {$modelName}",
        };

        return self::log(
            description: $description,
            subject: $model,
            event: $event,
            properties: $properties,
            logName: 'model_event'
        );
    }
}
