<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CryptoMovement extends Model
{
    use HasFactory;  

    protected $fillable = [
        'crypto_id',
        'code',
        'entry_date',
        'started_price',
        'ended_price',
    ];

   
}
