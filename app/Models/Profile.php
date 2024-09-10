<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enterprise_id',
        'type',
        'name',
        'email',
        'username',
        'work_position',
        'job_title',
        'company',
        'address',
        'bio',
        'phone',
        'photo',
        'cover_photo',
        'is_default',
        'active',
        'user_direct',
        'tiks',
        'private'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'profile_platforms', 'profile_id', 'platform_id')
            ->withPivot('path', 'direct', 'platform_order', 'label')
            ->orderBy('platform_order');
    }

}