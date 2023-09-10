<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public static function getAllCustomer(){
        $data = [];
       
        foreach (User::all() as $user) {
            $count =UserRole::where([
                ["user_id","=",$user->id],
                ["role","=","CUSTOMER"]
            ])->count();

            if($count >0){
                array_push($data,$user);
            }
        }
        return $data;
    }

    public function removeCustomer(string $id){
        $isCustomerRemove =false;
        $user =User::find($id);
        $isRemoved =$user->delete();
        $userRole = UserRole::where('user_id',$id);
        $isRemoveRole =$userRole->delete();
        if($isRemoved && $isRemoveRole){
            $isCustomerRemove =true;
        }
       
        return [
            'isCustomerRemove'=> $isCustomerRemove
        ];

    }
    
}
