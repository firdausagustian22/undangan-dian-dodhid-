<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public static function record(string $type, string $description, ?array $properties = null): self
    {
        return static::create([
            'type' => $type,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
