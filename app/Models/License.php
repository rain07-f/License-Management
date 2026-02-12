<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'owner_id',
        'generated_by',
        'plan_id',
        'license_key_hash',
        'license_key_display',
        'status',
        'expires_at',
        'max_domains',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function logs()
    {
        return $this->hasMany(LicenseLog::class);
    }
}
