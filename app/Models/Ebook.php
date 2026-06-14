<?php

namespace App\Models;

class Ebook extends BaseModel
{
    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
    ];
}
