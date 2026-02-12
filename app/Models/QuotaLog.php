<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotaLog extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'amount',
        'previous_quota',
        'current_quota',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
