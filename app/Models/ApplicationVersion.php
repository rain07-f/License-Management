<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationVersion extends Model
{
    protected $fillable = [
        'application_id',
        'version',
        'file_path',
        'file_size',
        'release_notes',
        'min_php_version',
        'min_wp_version',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
