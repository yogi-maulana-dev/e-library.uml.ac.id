<?php

namespace App\Models;

class BookRating extends BaseModel
{
    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
    ];
}
