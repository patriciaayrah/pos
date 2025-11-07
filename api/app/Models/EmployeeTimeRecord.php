<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class EmployeeTimeRecord extends Model
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'user_id',
        'time_in',
        'time_out',
        'break_in',
        'break_out',
        'hours_worked',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
