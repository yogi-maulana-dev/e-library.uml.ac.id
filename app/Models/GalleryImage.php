<?php

namespace App\Models;

class GalleryImage extends BaseModel
{
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
