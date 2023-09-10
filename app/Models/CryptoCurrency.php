<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CryptoCurrency extends Model
{
    use HasFactory;  

    protected $fillable = [
        'code',
        'name',
        'description',
        'current_price',
        'currency',
    ];

   
}
