<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = "product_variants";
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id'); // Ensure 'size_id' matches your database column
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id'); // Ensure 'color_id' matches your database column
    }
}

