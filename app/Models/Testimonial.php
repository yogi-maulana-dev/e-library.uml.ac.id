<?php

namespace App\Models;

class Testimonial extends BaseModel
{
    protected $casts = [
        'is_approved' => 'boolean',
    ];
}
