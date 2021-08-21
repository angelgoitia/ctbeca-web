<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'id', 'admin_id', 'lessSlp', 'lessPercentage', 'greaterSlp', 'greaterPercentage',
    ];

    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }
}
