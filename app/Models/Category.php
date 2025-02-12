<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_sv',
        'status'
    ];

    public function platforms()
    {
        return $this->hasMany(Platform::class);
    }
}
