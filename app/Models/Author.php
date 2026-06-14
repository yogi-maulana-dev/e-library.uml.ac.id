<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends BaseModel
{
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_author');
    }
}
