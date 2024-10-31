<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid', 'shop_id', 'integration_id', 'title', 'description', 'log_payload', 'trigger_payload', 'status'
    ];

    protected $casts = [
        'log_payload' => 'array',
        'trigger_payload' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($activityLog) {
            $activityLog->uid = str_unique();
        });
    }
}
