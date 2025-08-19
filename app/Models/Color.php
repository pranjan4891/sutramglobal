<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colors'; // Ensure table name matches your database
    protected $guarded = ['id'];
    protected $fillable = ['id', 'code', 'name'];
}
