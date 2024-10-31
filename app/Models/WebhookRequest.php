<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookRequest extends Model
{
    protected $fillable = [
        'uid', 'request_id', 'shop_id', 'custom_webhook_id', 'provider', 'topic', 'payload', 'headers', 'status', 'payload_hash'
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($webhookRequest) {
            $webhookRequest->uid = str_unique();
        });
    }

}
