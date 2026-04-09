<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action',
        'action_type',
        'module',
        'model_type',
        'model_id',
        'model_name',
        'old_data',
        'new_data',
        'description',
        'method',
        'url',
        'ip_address',
        'user_agent',
        'device_type',
        'metadata',
        'is_error',
        'error_message',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'metadata' => 'array',
        'is_error' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function model()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeErrors($query)
    {
        return $query->where('is_error', true);
    }

    // Helper methods
    public function getActionBadgeClassAttribute()
    {
        return match ($this->action) {
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            default => 'primary',
        };
    }

    public function getFormattedOldDataAttribute()
    {
        if (!$this->old_data) {
            return '-';
        }
        return json_encode($this->old_data, JSON_PRETTY_PRINT);
    }

    public function getFormattedNewDataAttribute()
    {
        if (!$this->new_data) {
            return '-';
        }
        return json_encode($this->new_data, JSON_PRETTY_PRINT);
    }
}
