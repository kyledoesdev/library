<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use HasFactory;

    public function getCreatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->inUserTimezone()->format('m/d/Y g:i A T');
    }

    public function getUpdatedAtAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->inUserTimezone()->format('m/d/Y g:i A T');
    }
}
