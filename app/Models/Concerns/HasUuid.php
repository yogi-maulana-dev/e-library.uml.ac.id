<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasUuid
{
    public function initializeHasUuid(): void
    {
        $this->incrementing = false;
        $this->keyType = 'string';
    }

    protected static function bootHasUuid(): void
    {
        static::creating(function ($model): void {
            if (empty($model->getKey())) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
