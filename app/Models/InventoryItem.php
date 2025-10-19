<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class InventoryItem extends Model
{
    
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'weight',
        'unit',
        'brand',
        'current_price',
        'supplier',
        'address',
        'contact_number'
    ];

    // Reverse relation
    public function inventoryStock()
    {
        return $this->hasMany(InventoryStock::class, 'item_id');
    }

    public function productIngredient()
    {
        return $this->hasMany(ProductIngredient::class, 'item_id');
    }
}
