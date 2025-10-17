<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class ProductIngredient extends Model
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'product_id',
        'item_id',
        'qty_used',
    ];
}
