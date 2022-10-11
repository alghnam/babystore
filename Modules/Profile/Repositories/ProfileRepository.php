<?php
namespace Modules\Profile\Repositories;

use App\GeneralClasses\MediaClass;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Entities\Movement;

// class ProfileRepository extends EloquentRepository implements ProfileRepositoryInterface{
class ProfileRepository extends EloquentRepository {



    public function show($model){
        $userId=auth()->guard('api')->user()->id;
        // $modelData=$model->where(['id'=>$userId])->with(['image','favorites','favorites.product','orders','orders.payment','orders.service'])->first();
        $modelData=$model->where(['id'=>$userId])->with(['image','favorites','favorites.product'])->first();
        return $modelData;
    }

    public function storeImage($request,$userId,$model){

            $user=$this->find($userId,$model);
        $data= $request->validated();
        if(!empty($data['image'])){
            if($request->hasFile('image')){
                $file_path_original_image_user= MediaClass::store($request->file('image'),'profile-images');//store profile image
                $data['image']=$file_path_original_image_user;
            }else{
                $data['image']=$user->image;
            }
            $user->image()->create(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'Modules\Auth\Entities\User']);
        }
    }

    public function update($request,$id,$model){

        $user=$this->find($id,$model);
        $data= $request->validated();
                $enteredData=  Arr::except($data ,['image']);

                
           $userUpdated=$user->update($enteredData);
           
        if(!empty($data['image'])){
            if($request->hasFile('image')){
                $file_path_original= MediaClass::store($request->file('image'),'profile-images');//store profile image
                                    $file_path_original_without_public= str_replace("public/","",$file_path_original);

                $data['image']=$file_path_original_without_public;
                
                if($user->image){
                    $user->image()->update(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'Modules\Auth\Entities\User']);
          
                }else{
          
                    $user->image()->create(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'Modules\Auth\Entities\User']);
                }
            }else{
                $data['image']=$user->image;
            }
      }
            // dd($user->image);
      //لو مدخخل الايميل حعطيه 10 نقاط 
      //نوع التقاط مكتسبة 
      if(!empty($user->email)){
            
        $wallet=Wallet::where(['user_id'=>$user->id])->first();
        if(empty($wallet)){//اي هادا اليوزر ما الو لسا محفظة في النظام فاول عملية ايداع بيتم كريتة محفظة لالو 
                $wallet=new Wallet();
                $wallet->user_id=$user->id;
                $wallet->save();
              }
         $movementEmailInsertedCount= Movement::where(['wallet_id'=>$wallet->id,'name'=>'email inserted'])->count();
         if($movementEmailInsertedCount==0){
              $movementWallet=new Movement();
                // $movementWallet->name='email inserted';
                $movementWallet->name='تم اضافة ايميل';
                $movementWallet->value=10;
                $movementWallet->type=0;//Acquired
                $movementWallet->wallet_id=$wallet->id;
                $movementWallet->remaining_wallet_points=$wallet->amount+10;//عشان مع كل حركة اكون عارف كم صار رصيد المحفظة 
                $movementWallet->save();
                // dd($wallet->amount+10);
                $total=$wallet->amount+10;
                //تزويد هاي النقاط ع محفظتي
                $wallet->amount=$wallet->amount+10;
                $wallet->save();
         }
         //الس ما بزبط اضيف لنفس المحفظة مرة تانية لنفس السبب متلا هاي المحفظة مضيوفلها من قبل 10 نقاط مكتسبة عشان اضافة الايميل فما بزبط تاني مرة يضيف ايميلو اضيفلو تاني لا خلص 
         
        //  foreach($movements as $movement){
        //     //  echo $movement;
        //      if($movement->name!=="email inserted"){
                 
               
        //      }
        //  }
      }
      return $user;
    }
    
    
    public function updatePassword($request,$model){
        $userId=auth()->guard('api')->user()->id;
       $user= $model->where(['id'=>$userId])->first();
       $data=$request->validated();
            $loggedInPassword=   Storage::get($user->id.'-loggedInPassword');
            //check if oldPass = new pass 
                if($data['old_password']==$data['new_password']){
                    // return __('cannt updated it , because new password equal your password  ');
                    return 'لا يمكنك التعديل على كلمة سرك القديمة لان كلمة السر الجديدة التي ادخلتها مثل القديمة ';
                }
                if($loggedInPassword==$data['old_password']){
                    $newPassword=Hash::make($data['new_password']);
                    // $confirmationNewPassword=Hash::make($data['confirmation_new_password']);
                    if($data['new_password']==$data['confirmation_new_password']){
                        $user->password=$newPassword;
                        $user->save();
                        return $user;
                    }else{
                    //    return __('new password not match with confirmation new password');
                        return 'كلمة المرور غير متطايقة مع تاكيد كلمة المرور';
                    }
                }else{
                    // return __('old password not correct , pls try again ');
                    return 'كلمة المرور القديمة غير صحيحة , الرجاء المحاولة مرة اخرى';
                }

    }


    public function requestDocumentation($model,$userId){
      $user=  $model->find($userId);
      if($user->documentation===1){
        //   return __('you already request documentation ');
          return 'تم ارسال طلبك من قبل بالفعل';
      }else{
          $user->documentaion=1;//request documentation under reviewing
          $user->save();
            return $user;
      }
    }
    public function acceptingOnRequestDocumentation($model,$userId){
        $user=  $model->find($userId);
        if($user->documentation==2){
          //  return __('you already make accept request documentation ');
            return 'طلبك بالطبع تم قبوله';
        }else{
            $user->documentaion=2;//request documentation has been accepted
            $user->save();
            return $user;
        }
    }
}