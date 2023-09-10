<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class KeyGeneratorController extends Controller
{
    
    public static $code ="crypto";
    /**
     * Handle an incoming authentication request.
     */
    public function create(Request $request)
    {
        if($request->header('X-Code')== self::$code){
            if(Auth::check()){
                $user =  Auth::user();
                $token =$user->createToken("auth_token")->plainTextToken;
                return [
                    "code"=>"200",
                    "auth_token"=>$token
                ];
            }
        }
        return [
            "code"=>"401",
            "message"=>"Authorization Failed"
        ];
        
        
    }

    /**
     * Destroy an authenticated session.
     */
    
}
