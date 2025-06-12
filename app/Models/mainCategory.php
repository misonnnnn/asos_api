<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class mainCategory extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'main_category';
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_unique_id',
        'main_category_id',
        'name',
        'parent_category_id',
        'sub_category_id',
        'child_category_id',
        'status',
        'extra_info',
    ];
}
