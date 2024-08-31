<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'asin',
        'image',
        'reg_price',
        'price',
        'pro',
        'tar_price',
        'changed_time',
        'in_stock',
        'url',
        'inter',
        'user_id',
        'is_notified',
        'notified_time',
        'error',
    ];
}
