<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'license_id',
        'user_id',
        'domain',
        'ip_address',
        'action',
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
