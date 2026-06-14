<?php

namespace App\Models;

class FAQ extends BaseModel
{
    protected $table = 'faqs';

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
