<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Product extends Model
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'category_id',
        'sub_category_id',
        'price',
    ];

    // Define relationship to Category
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function productSubCategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }

      // Reverse relation
    public function productIngredient()
    {
        return $this->hasMany(ProductIngredient::class, 'product_id');
    }
}
