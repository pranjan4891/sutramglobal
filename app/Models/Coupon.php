<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value', 'min_order_value', 'max_discount_amount', 'start_date', 'end_date', 'usage_limit', 'usage_per_customer', 'allow_category', 'status',
    ];
}
