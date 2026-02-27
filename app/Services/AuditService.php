<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an action
     *
     * @param string $action The action performed (create, update, delete, check_in, check_out, cancel)
     * @param mixed $model The model being affected
     * @param array|null $oldValues The old values before the change
     * @param array|null $newValues The new values after the change
     * @param string|null $description Optional description
     * @return AuditLog
     */
    public static function log(string $action, $model, ?array $oldValues = null, ?array $newValues = null, ?string $description = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => is_object($model) ? get_class($model) : $model,
            'model_id' => is_object($model) ? $model->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Log a creation action
     */
    public static function logCreate($model, ?string $description = null): AuditLog
    {
        return self::log('create', $model, null, $model->toArray(), $description);
    }

    /**
     * Log an update action
     */
    public static function logUpdate($model, ?array $oldValues = null, ?string $description = null): AuditLog
    {
        return self::log('update', $model, $oldValues, $model->toArray(), $description);
    }

    /**
     * Log a delete action
     */
    public static function logDelete($model, ?string $description = null): AuditLog
    {
        return self::log('delete', $model, $model->toArray(), null, $description);
    }

    /**
     * Log a check-in action
     */
    public static function logCheckIn($model, ?string $description = null): AuditLog
    {
        return self::log('check_in', $model, null, null, $description ?? 'Guest checked in');
    }

    /**
     * Log a check-out action
     */
    public static function logCheckOut($model, ?string $description = null): AuditLog
    {
        return self::log('check_out', $model, null, null, $description ?? 'Guest checked out');
    }

    /**
     * Log a cancellation action
     */
    public static function logCancel($model, ?string $description = null): AuditLog
    {
        return self::log('cancel', $model, null, null, $description ?? 'Reservation cancelled');
    }

    /**
     * Log a login action
     */
    public static function logLogin($user, ?string $description = null): AuditLog
    {
        return self::log('login', $user, null, null, $description ?? 'User logged in');
    }

    /**
     * Log a logout action
     */
    public static function logLogout($user, ?string $description = null): AuditLog
    {
        return self::log('logout', $user, null, null, $description ?? 'User logged out');
    }
}
