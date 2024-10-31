<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'logo', 'icon', 'pointer', 'type', 'status',
    ];


    protected static function booted()
    {
        static::creating(function ($app) {
            $app->uid = str_unique();
        });
    }

    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->logo);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class, 'action_id', 'id');
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(Credential::class, 'app_id', 'id');
    }
}
