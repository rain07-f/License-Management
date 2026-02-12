<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'license_id',
        'domain_name',
        'activated_by',
        'activated_at',
        'last_check_at',
        'status',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'last_check_at' => 'datetime',
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function activator()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }
}
