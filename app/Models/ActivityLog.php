<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use Concerns\HasUuid;

    protected $guarded = [];

    protected $casts = [
        'changes' => 'array',
    ];
}
