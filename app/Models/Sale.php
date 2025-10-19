<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Sale extends Model
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_type',
        'amount_tendered',
        'change_due',
        'status',
        'notes',
    ];

    // Define relationship to Category
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
