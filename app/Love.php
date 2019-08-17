<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Love extends Authenticatable
{
    use Notifiable;

    public $incrementing = false;
    public $timestamps = false;

    public function content() {
        return $this->belongsTo('App\Content');
    }
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "love";
    protected $fillable = [
        'content_id', 'id',
    ];

}
