<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the action label
     */
    public function getActionLabel(): string
    {
        $labels = [
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'check_in' => 'Checked In',
            'check_out' => 'Checked Out',
            'cancel' => 'Cancelled',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
        ];

        return $labels[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get the action color for badges
     */
    public function getActionColor(): string
    {
        $colors = [
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'check_in' => 'green',
            'check_out' => 'gray',
            'cancel' => 'red',
            'login' => 'green',
            'logout' => 'gray',
        ];

        return $colors[$this->action] ?? 'gray';
    }

    /**
     * Get the model name
     */
    public function getModelName(): string
    {
        $parts = explode('\\', $this->model_type);
        return end($parts);
    }

    /**
     * Get formatted old values
     */
    public function getFormattedOldValues(): string
    {
        if (!$this->old_values) {
            return 'N/A';
        }

        return json_encode($this->old_values, JSON_PRETTY_PRINT);
    }

    /**
     * Get formatted new values
     */
    public function getFormattedNewValues(): string
    {
        if (!$this->new_values) {
            return 'N/A';
        }

        return json_encode($this->new_values, JSON_PRETTY_PRINT);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by model type
     */
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
