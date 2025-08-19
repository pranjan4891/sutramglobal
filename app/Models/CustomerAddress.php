<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name','email', 'address1', 'address2', 'city', 'state', 'country', 'zip_code', 'remark', 'type', 'mobile', 'alertnate_mobile', 'status','created_at', 'updated_at'];
}
