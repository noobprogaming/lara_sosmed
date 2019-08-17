<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Informatika extends Model
{

    public $incrementing = false;
    public $timestamps = false;

    protected $table = "users";
    protected $fillable = ['id', 'name', 'email', 'password'];

    protected $hidden = ['password'];

    protected $casts = [
        'id' => 'array',
        'name' => 'array',
        'email' => 'array',
        'password' => 'array',
    ];

}
