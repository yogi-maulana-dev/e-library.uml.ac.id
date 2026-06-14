<?php

namespace App\Models;

class Gallery extends BaseModel
{
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
