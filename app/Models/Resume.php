<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    //
    protected $fillable = [
        'image',
        'name',
        'objectives',
        'birthday',
        'contact',
        'email',
        'address',
        'education',
        'skills',
        'applications',
    ];

    protected $casts = [
        'education' => 'array',
        'skills' => 'array',
        'applications' => 'array',
    ];
}
