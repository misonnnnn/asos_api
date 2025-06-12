<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    
    protected $table = 'products';
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_unique_id',
        'product_code',
        'category_id',
        'name',
        'brand_name',
        'price',
        'price_json',
        'colour',
        'url',
        'status',
        'additional_images_urls',
        'extra_info',
    ];
}
