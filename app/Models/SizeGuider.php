<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeGuider extends Model
{
    use HasFactory;
    protected $table = 'size_guiders';
    protected $fillable = ['cat_id', 'sub_cat_id', 'size_id', 'chest', 'length', 'shoulder', 'sleeve', 'waist'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_cat_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
