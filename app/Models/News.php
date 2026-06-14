<?php

namespace App\Models;

class News extends BaseModel
{
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
}
