<?php
namespace App\Repositories\Auth\Login;

use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;
class LoginRepository extends EloquentRepository implements LoginRepositoryInterface{

    public function login($request){
        $data=$request->validated();
        if(!auth()->attempt($data)){
            // return __('Invalid credentials');
            return 'بيانات الدخول غير صحيحة ';
        }else{


            // if(!empty(Storage::get('session_id'))){
            //     //get all data from all tables have this session -> to store this data for this user instead session
            //     $cart=Cart::where(['session_id'=>Storage::get('session_id')])->first();
            //     $cart->user_id=auth()->user()->id;
            //     $cart->save();
                
            // }
                //بعد التاكد من الريجستر تمام واللوجين متلا بيتم انشاء كل الاسياء اللي بدي استخدمها بالستوريج 
                // $this->putStorage();
            return 200;
        }

    }
    
    public function logout($request){
        $request->user()->token()->revoke();
                $user=auth()->guard('api')->user();

                                   Storage::put($user->id.'-token',null);

        return true;
    }
    
    // methods overrides

}