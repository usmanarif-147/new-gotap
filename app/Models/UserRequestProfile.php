<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'enterprise_id',
        'user_id',
        'status',
    ];
}
