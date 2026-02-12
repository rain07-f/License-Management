<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration_days',
        'domain_limit',
        'price',
    ];

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
