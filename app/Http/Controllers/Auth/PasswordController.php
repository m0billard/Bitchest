<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $response =[];
        $res = new \stdClass();
    
        $validated = $this->validateChangePass($request);
        if(empty((array)$validated) ){
            $request->user()->update([
                'password' => Hash::make($request->password)
            ]);
            $request->user()->save();
            $res->status ="200";
            $res->isUpdateSuccess =true;
            $res->error=$validated;
            // array_push($response,$res);
        }else{
            $res->status ="400";
            $res->isUpdateSuccess =false;
            $res->error=$validated;
            // array_push($response,$res);
        }
        
       return $res;

        
    }
    private function validateChangePass(Request $request){
        $response = new \stdClass();
        if(!Hash::check($request->current_password, Auth::user()->password)){
            // Log::info("Current Password: ".Hash::make($request->current_password));
            // Log::info("DBPassword: ".Auth::user()->password);
            $response->current_password ="Incorrect Password";
            
        }else if(Hash::check($request->password, Auth::user()->password)){
            $response->password ="Your current password can't be with new password. ";
        }
        
        if(strlen($request->password) <8){
            $response->password ="New Password Should not be less than 8 character.";
        }
        if($request->password != $request->password_confirmation){
            $response->password_confirmation ="Password Confimation is wrong";
        }
        return $response;

    }
}
