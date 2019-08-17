<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Friend extends Authenticatable
{
    use Notifiable;

    public $incrementing = false;
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "friend";
    protected $fillable = [
        'user_id', 'friend_id',
    ];
}
