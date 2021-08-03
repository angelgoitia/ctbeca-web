<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalSlp extends Model
{
    protected $fillable = [
        'id', 'player_id', 'total', 'daily', 'created_at'
    ];

    public function player()
    {
        return $this->belongsTo('App\player', 'player_id');
    }
}
