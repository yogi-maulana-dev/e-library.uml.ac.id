<?php

namespace App\Models;

class Event extends BaseModel
{
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
    ];
}
