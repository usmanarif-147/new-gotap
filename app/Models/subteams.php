<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subteams extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'enterprise_id',
    ];

    public function profiles()
    {
        // Many-to-many relationship between Subteam and Profile
        return $this->belongsToMany(Profile::class, 'subteam_profiles', 'subteam_id', 'profile_id');
    }
}
