<?php

namespace App\Models;

use Google\Service\CloudBuild\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomWebhook extends Model
{
    protected $fillable = [
        'shop_id', 'unique_code', 'status'
    ];

    public function webhookEvent(): HasOne
    {
        return $this->hasOne(WebhookEvent::class, 'custom_webhook_id');
    }
}
