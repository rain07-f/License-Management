<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainActivityLog extends Model
{
    protected $fillable = [
        'domain_id',
        'event_type',
        'message',
        'ip_address',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
