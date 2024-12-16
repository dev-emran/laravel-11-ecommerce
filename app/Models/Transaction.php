<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable =[
        'user_id',	'order_id',	'method',	'status',	
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
