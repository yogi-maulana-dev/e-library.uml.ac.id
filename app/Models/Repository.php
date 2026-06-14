<?php

namespace App\Models;

class Repository extends BaseModel
{
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
