<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CryptoCurrency;
use App\Models\CryptoMovement;
use App\Models\UserCrypto;
use App\Models\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use stdClass;

class CryptoCurrencyController extends Controller
{

    private static $days = ["1","2","3","4","5","6",
    "7","8","9","10","11","12","13","14","15","16","17","18",
    "19","20","21","22","23","24","25","26","27","28","29","30","31"];
   
    public static function getAllCrypto(){
        return CryptoCurrency::all();
    }

    public function transaction($cryptocode){
        $crypto = CryptoCurrency::where('code',$cryptocode)->first();
        $now = date("Y-m-d") ;
        Log::info("current Date: ".$now);
        $labels = [];
        $datas = [];

        foreach (array_reverse(self::$days) as $day) {
            $entry_date= date('Y-m-d', strtotime('-'.$day.' days'));
            
                $movement =$this->checkAndInsert($entry_date,$crypto);
                $entry_date=date_create($entry_date);
                array_push($labels,date_format($entry_date,"Y-M-d"));
                array_push($datas,$movement->ended_price);
            
        }
        $now=date_create($now);
        array_push($labels,date_format($now,"Y-M-d"));
        $movement =$this->checkAndInsert($now,$crypto);
        array_push($datas,$movement->ended_price);
        $crypto = $this->updateCurrentPrice($movement->ended_price,$crypto);
        
        // Log::info("Response: ".json_encode($labels));
        // $movements = CryptoMovement::where('code',$cryptocode)->get();

        return Inertia::render('TransactCrypto', [
            'checkIfAdmin' => UserRole::checkIfAdmin(),
            'cryptoInfo' => $crypto,
            'labels'=>$labels,
            'datas'=>$datas,
        ]);
    }

    public function generateTransactions(Request $request){
        $year =$request->year;
        $month = $request->month;
        $code = $request->code;
        $crypto_id = $request->crypto_id;
        $arr = [];
        $count =  CryptoMovement::where([
            ['crypto_id','=',$crypto_id],
            ['entry_date','LIKE',"%".$year."-".$month."%"]
        ])->count();
        Log::info("count:".$count);
        if($count >0){
            return CryptoMovement::where([
                ['crypto_id','=',$crypto_id],
                ['entry_date','LIKE',"%".$year."-".$month."%"]
            ])->get();
        }
        foreach (self::$days as $day) {
            $entryDate = $year."-".$month."-".$day;
            if(!$this->isWeekend($entryDate)){
                $check = CryptoMovement::where([
                    ['crypto_id','=',$crypto_id],
                    ['entry_date','=',$entryDate]
                ])->count();
                if($check==0){
                    $movement = array(
                        "entry_date"=>$entryDate,
                        "code"=>$code,
                        "crypto_id"=>$crypto_id,
                        "started_price"=>$this->getCotationFor($code),
                        "ended_price"=>$this->getCotationFor($code)
                    );
                    // $movement->entry_date = $entryDate;
                    // $movement->code =$code;
                    // $movement->crypto_id =$crypto_id;
                    // $movement->ended_price =$this->getCotationFor($code);
                    array_push($arr,$movement);
                }
                
            }
          }
          CryptoMovement::insert($arr);
          return $arr;
    }

    function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }

    public function checkAndInsert($entry_date,$crypto){
        $movement = CryptoMovement::where([
            ['crypto_id','=',$crypto->id],
            ['entry_date','=',$entry_date]
        ])->get();
        if(count($movement) ==0){
           $movement=  CryptoMovement::create([
                "entry_date"=>$entry_date,
                "code"=>$crypto->code,
                "crypto_id"=>$crypto->id,
                "started_price"=>$crypto->current_price+$this->getCotationFor($crypto->name),
                "ended_price"=>$crypto->current_price+$this->getCotationFor($crypto->name)
            ]);
            return $movement;
        }
        return $movement[0];
    }

    public function updateCurrentPrice($price,$crypto){	
        $crypto =CryptoCurrency::find($crypto->id);
        $crypto->current_price =$price;
        $crypto->save();

        return $crypto;
    }


    public static function getFirstCotation($cryptoname){
        $response = ord(substr($cryptoname,0,1)) + rand(0, 10);

        if($response <0){
            return self::getFirstCotation($cryptoname);
        }
        return $response;

    }

    public function getCotationFor($cryptoname){	
        $response = ((rand(0, 99)>40) ? 1 : -1) * ((rand(0, 99)>49) ? ord(substr($cryptoname,0,1)) : ord(substr($cryptoname,-1))) * (rand(1,10) * .01);
        if($response <0){
            return $this->getCotationFor($cryptoname);
        }
        return $response;
    }

    public function buyCrypto(Request $request){
        $id = Auth::id();
        $user = Auth::user();
        $isBuyingSuccess = false;
        $message='';
        if($user->wallet_cash > $request->total_amount){
           $check =  UserCrypto::where([
            ['user_id','=',$id],
            ['crypto_id','=',$request->crypto_id],
            ['status','=','ACTIVE']
           ])->get();
           if(count($check)>0){
            $userCrypto = UserCrypto::find($check[0]->id);
            $userCrypto->bought_price =$request->current_price;
            $userCrypto->quantity = $userCrypto->quantity+$request->quantity;
            $userCrypto->total_bought_amount = $userCrypto->total_bought_amount+$request->total_amount;
            $userCrypto->save();
           }else{
            $userCrypto =  UserCrypto::create([
                'user_id'=>$id,
                'crypto_id'=>$request->crypto_id,
                'bought_price'=>$request->current_price,
                'quantity'=>$request->quantity,
                'total_bought_amount'=>$request->total_amount,
                'status'=>'ACTIVE'
            ]);
           }
            
            $user->wallet_cash = $user->wallet_cash-$request->total_amount;
            $user->save();
            if($userCrypto != null){
                $isBuyingSuccess=true;
            }   
        }else{
            $message='Not enough cash in your wallet';
        }
           
        return [
            'isBuyingSuccess'=>$isBuyingSuccess,
            'message'=>$message
        ];
    }

    public function sellCrypto(Request $request){
        Log::info("Request: ".json_encode($request->all()));
        $id = Auth::id();
        $user = Auth::user();
        $isSellingSuccess = false;
        $message = '';

        $userCrypto = UserCrypto::where('user_id',$id)
        ->where('crypto_id',$request->crypto_id)
        ->where('status','ACTIVE')->first();
        if($userCrypto !=null){
           if($userCrypto->quantity == $request->quantity){
            $userCrypto->status = 'INACTIVE';
            $userCrypto->sold_amount = $request->total_selling_amount;
            $userCrypto->save();
            $user->wallet_cash = $user->wallet_cash+$request->total_selling_amount;
            $user->save();
            $isSellingSuccess=true;
           }else if($userCrypto->quantity >$request->quantity){
            $userCrypto->quantity= $userCrypto->quantity-$request->quantity;
            $userCrypto->sold_amount = $request->total_selling_amount;
            $userCrypto->save();
            $isSellingSuccess=true;
            $user->wallet_cash = $user->wallet_cash+$request->total_selling_amount;
            $user->save();
           }else{
            $message='Not enough holdings to sell';
           }
           
        }else{
            $message='You have no holdings for this crypto';
        }

        return [
            'isSellingSuccess'=>$isSellingSuccess,
            'message'=>$message
        ];

    }

    public static function getAllHoldings(){
        $id = Auth::id();
        return DB::table('user_cryptos')
        ->leftJoin('crypto_currencies', 'crypto_currencies.id', '=', 'user_cryptos.crypto_id')
        ->where('user_id',$id)->where('status','ACTIVE')->get();
    }
    public static function getAllSoldHoldings(){
        $id = Auth::id();
        return DB::table('user_cryptos')
        ->leftJoin('crypto_currencies', 'crypto_currencies.id', '=', 'user_cryptos.crypto_id')
        ->where('user_id',$id)->where('status','INACTIVE')->get();
    }
}
