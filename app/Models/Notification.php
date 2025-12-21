<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'icon',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getIconClass()
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'order' => 'fa-shopping-bag',
            'promotion' => 'fa-tag',
            'system' => 'fa-info-circle',
            'message' => 'fa-envelope',
            'alert' => 'fa-exclamation-triangle',
            default => 'fa-bell'
        };
    }

    public function getColorClass()
    {
        return match($this->type) {
            'order' => 'text-blue-500',
            'promotion' => 'text-green-500',
            'system' => 'text-gray-500',
            'message' => 'text-purple-500',
            'alert' => 'text-red-500',
            default => 'text-gray-500'
        };
    }
}

