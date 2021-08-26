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
        'id', 'name', 'email', 'phone', 'telegram', 'urlCodeQr', 'reference', 'emailGame', 'passwordGame', 'wallet', 'urlCodeQr', 'dateClaim', 'admin_id', 'tokenFCM'
    ];

    protected $hidden = [
        'passwordGame',
    ];

    public function getAuthPassword()
    {
        return $this->passwordGame;
    }

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

    public function claims()
    {
        return $this->hasMany('App\Claim');
    }

    public function claimsApi()
    {
        return $this->hasMany(Claim::class)->orderBy('date', 'DESC');
    }

    public function group()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    } 
    
}
