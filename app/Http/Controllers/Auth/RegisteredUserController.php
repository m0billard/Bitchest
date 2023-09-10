<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    private static $defaultPassword ="12345678";
    private static $ADMINISTRATOR="ADMIN";
    private static $CUSTOMER="CUSTOMER";
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $response = new \stdClass();
        $validated = $this->validateRegistrationRequest($request);
        if(!empty((array)$validated) ){
            $response->status ="400";
            $response->isUserCreated =false;
            $response->error=$validated;
            return $response;
        }
        $role =$request->role;
        if(UserRole::checkIfAdmin()){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(self::$defaultPassword),
                'contact_number' =>$request->contact_number,
                'address'=>$request->address,
                'wallet_cash' =>500,
            ]);
            $userRole = UserRole::create([
                'user_id'=>$user->id,
                'role'=>($role =='admin'?self::$ADMINISTRATOR:self::$CUSTOMER)
            ]);

            event(new Registered($user));

            $response->status ="200";
            $response->isUserCreated =true;
            return $response;
        }else{
            $validated->error="You are not allowed to register a user";
            $response->status ="401";
            $response->isUserCreated =false;
            $response->error=$validated;
            return $response;
        }
       
    }
    private function validateRegistrationRequest(Request $request){
        $response = new \stdClass();
        if(User::where('email',$request->email)->count()>0){
            $response->email ='Email Address already exist';
        }
        return $response;

    }
}
