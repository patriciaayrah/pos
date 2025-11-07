<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class SaleItem extends Model
{
    
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'discount',
        'total_price'
    ];

    // Define relationship to sale
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}

