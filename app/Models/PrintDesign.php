<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintDesign extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'subcategory_slug',
        'name',
        'slug',
        'image',
        'status',
        'position',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
}
