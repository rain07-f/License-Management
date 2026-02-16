<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'is_active',
    ];

    /**
     * Scope a query to only include active API keys.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate a secure API key.
     * Format: RAIN07-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
     */
    public static function generate(): string
    {
        $prefix = 'RAIN07-';
        $random = Str::random(32);
        return $prefix . strtoupper($random);
    }

    /**
     * Hash the API key for storage.
     */
    public static function hash(string $key): string
    {
        return hash('sha256', $key);
    }
}
