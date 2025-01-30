<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaignEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'enterprise_id',
        'message',
        'total',
    ];
}
