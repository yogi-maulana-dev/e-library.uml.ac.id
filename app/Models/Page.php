<?php

namespace App\Models;

class Page extends BaseModel
{
    protected $casts = [
        'is_published' => 'boolean',
    ];
}
