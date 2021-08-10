<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Player extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'players';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'phone', 'telegram', 'urlCodeQr', 'reference', 'user', 'emailGame', 'passwordGame', 'wallet', 'urlCodeQr'
    ];

    protected $hidden = [
        'password',
    ];

    public function totalSlp()
    {
        return $this->hasMany('App\TotalSlp');
    }

    public function lastSLP()
    {
        return $this->hasOne(TotalSlp::class)->orderBy('created_at', 'DESC');
    }

    public function animals()
    {
        return $this->hasMany('App\Animal');
    }
}
