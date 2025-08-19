<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes'; // Ensure table name matches your database
    protected $guarded = ['id'];
    protected $fillable = ['id', 'sort', 'code', 'category', 'type', 'chest', 'waist', 'length'];
}

