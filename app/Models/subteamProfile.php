<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subteamProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'subteam_id',
    ];
}
