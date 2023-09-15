<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
