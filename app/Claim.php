<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'id', 'player_id', 'date', 'totalPlayer', 'totalManager', 'total',
    ];

    public function player()
    {
        return $this->belongsTo('App\Player', 'player_id');
    }
}
