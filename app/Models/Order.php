<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['date'];

    // In Order.php
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

}
