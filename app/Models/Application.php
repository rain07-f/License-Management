<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function versions()
    {
        return $this->hasMany(ApplicationVersion::class)->orderBy('created_at', 'desc');
    }
    
    public function latestVersion()
    {
        return $this->hasOne(ApplicationVersion::class)->latestOfMany();
    }
}
