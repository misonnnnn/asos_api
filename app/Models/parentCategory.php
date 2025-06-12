<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\subCategory;

class parentCategory extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'parent_category';
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'external_unique_id',
        'status',
    ];

    public function subCategories()
    {
        return $this->hasMany(subCategory::class, 'parent_category_id','external_unique_id');
    }
}
