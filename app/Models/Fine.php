<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends BaseModel
{
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
