<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebhookEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid', 'app_id', 'type', 'custom_webhook_id', 'name', 'topic', 'payload', 'status'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($webhookEvent) {
            $webhookEvent->uid = str_unique();
        });
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }

    public function customWebhook(): BelongsTo
    {
        return $this->belongsTo(CustomWebhook::class, 'custom_webhook_id');
    }

}
