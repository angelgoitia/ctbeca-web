<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'id', 'admin_id', 'lessSlp', 'lessPercentage', 'higherSlp', 'higherPercentage',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }
}
