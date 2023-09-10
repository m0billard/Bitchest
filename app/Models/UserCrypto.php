<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserCrypto extends Model
{
    use HasFactory;  

    protected $fillable = [
        'user_id',
        'crypto_id',
        'bought_price',
        'quantity',
        'total_bought_amount',
        'sold_amount',
        'status'
    ];

   
}
