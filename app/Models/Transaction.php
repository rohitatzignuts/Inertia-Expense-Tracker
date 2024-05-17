<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    public $guarded = [];

    public function getTimeAttribute(): string
    {
        return date('d M Y, H:i:s', strtotime($this->attributes['created_at']));
    }
}
