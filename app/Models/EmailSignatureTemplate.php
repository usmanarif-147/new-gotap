<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSignatureTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'name',
        'fields',
        'images',
        'preview_data',
    ];

    protected $casts = [
        'fields' => 'array',
        'images' => 'array',
        'preview_data' => 'array',
    ];
}
