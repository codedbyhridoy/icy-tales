<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\State;
use App\Models\Order;

class City extends Model
{
    protected $guarded = ['id'];

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }
}
