<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = [
        'id', 'player_id', 'name', 'code', 'type', 'nomenclature', 'image',
    ];

    public function player()
    {
        return $this->belongsTo('App\player', 'player_id');
    }
}
