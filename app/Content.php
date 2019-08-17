<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Content extends Authenticatable
{
    use Notifiable;

    public $incrementing = false;
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User');
    }
    public function love() {
        return $this->hasMany('App\Love');
    }
    public function comment() {
        return $this->hasMany('App\Comment');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'content_id';
    protected $table = "content";
    protected $fillable = [
        'content_id', 'id', 'content', 'file', 'time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'time' => 'datetime',
    ];
}
