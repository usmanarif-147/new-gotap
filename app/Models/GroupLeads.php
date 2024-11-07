<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupLeads extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'lead_id'
    ];
}
