<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'name',
        'relationship',
        'message',
        'attendance',
        'is_pinned',
        'is_approved',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
