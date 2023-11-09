<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Birthday extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'title', 
        'phone_number', 
        'body', 
        'birthday_date', 
        'user_id',
        'group_id',
    ];
}
