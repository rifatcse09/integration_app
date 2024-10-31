<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function ($integration) {
            $integration->uid = str_unique();
        });
    }
    protected $fillable = [
        'name', 'action_id', 'trigger_id', 'shop_id', 'action_credential_id', 'trigger_credential_id', 'event_id', 'payload', 'status'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function actionCredential(): BelongsTo
    {
        return $this->belongsTo(Credential::class, 'action_credential_id','id');
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, 'action_id','id');
    }
}
