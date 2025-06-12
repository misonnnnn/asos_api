<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\mainCategory;

class childCategory extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'child_category';
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_unique_id',
        'name',
        'parent_category_id',
        'sub_category_id',
        'status',
        'extra_info',
    ];

    public function mainCategories()
    {
        return $this->hasMany(mainCategory::class, 'child_category_id', 'external_unique_id');
    }
}
