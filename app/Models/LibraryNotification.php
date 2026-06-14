<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryNotification extends Model
{
    use Concerns\HasUuid;

    protected $table = 'notifications';

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
