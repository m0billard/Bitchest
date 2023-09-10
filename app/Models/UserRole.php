<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use \Exception;

class UserRole extends Model
{
    use HasFactory;  

    protected $fillable = [
        'user_id',
        'role',
    ];

    public static function checkIfAdmin(){
       
        $user_id = Auth::id();
        $count =static::where([
            ['user_id',"=",$user_id],
            ['role',"=",'ADMIN']
        ])->count();
       
        if($count >0){
            return true;
        }else{
            return false;
        }
        
    }

    
}
