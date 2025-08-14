<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailRead extends Model
{
    use HasFactory;
    protected $fillable = ['compaign_id', 'email', 'read_at'];
}
