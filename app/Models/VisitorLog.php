<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use Concerns\HasUuid;

    protected $guarded = [];
}
