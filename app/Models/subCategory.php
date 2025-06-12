<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\ChildCategory;

class subCategory extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'sub_category';
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_unique_id',
        'name',
        'parent_category_id',
        'status',
        'extra_info',
    ];

    public function childCategories()
    {
        return $this->hasMany(childCategory::class, 'sub_category_id', 'external_unique_id');
    }
}
