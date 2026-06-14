<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Borrowing extends BaseModel
{
    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime',
        'approved_at' => 'datetime',
        'is_overdue' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }
}
