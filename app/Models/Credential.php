<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credential extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function ($credential) {
            $credential->uid = str_unique();
        });
    }

    protected $fillable = [
        'shop_id', 'app_id', 'name', 'scopes', 'scope_hash', 'secrets', 'source', 'status'
    ];

    protected $guarded = ['shop_id'];

    protected array $dates = ['deleted_at'];

    protected $casts = [
        'secrets' => 'array',
    ];

//    public function actions()
//    {
//        return $this->belongsToMany(App::class)->using(AppCredential::class);
//    }

    public function actionIntegrations(): HasMany
    {
        return $this->hasMany(Integration::class, 'action_credential_id', 'id');
    }
    public function triggerIntegrations(): HasMany
    {
        return $this->hasMany(Integration::class, 'trigger_credential_id', 'id');
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }
}
