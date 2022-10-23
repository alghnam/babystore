<?php
namespace Modules\Profile\Repositories;

use App\GeneralClasses\MediaClass;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Wallet\Entities\Wallet;
use Modules\Movement\Entities\Movement;
use Modules\Profile\Repositories\ProfileRepositoryInterface;
class ProfileRepository extends EloquentRepository implements ProfileRepositoryInterface{



    public function show($model){
        $userId=auth()->guard('api')->user()->id;
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
        $userId=$user->id;
          $points=10;
          $name='اضافة صورة';
        if(!empty($data['image'])){
            
            if($request->hasFile('image')){
                $file_path_original= MediaClass::store($request->file('image'),'profiles-images');//store profile image
                                    $file_path_original_without_public= str_replace("public/","",$file_path_original);

                $data['image']=$file_path_original_without_public;
                
                if($user->image){
                    $user->image()->update(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'Modules\Auth\Entities\User']);

                    $this->addReward($userId,$points,$name);
                }else{
                          $this->addReward($userId,$points,$name);

                    $user->image()->create(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'Modules\Auth\Entities\User']);
                }
            }else{
                $data['image']=$user->image;
            }
      }
      if(!empty($user->email)){
          $userId=$user->id;
          $points=10;
          $name='اضافة ايميل';
            $this->addReward($userId,$points,$name);
       

         
 
      }
      return $user;
    }
    public function addReward($userId,$points,$name){
         $wallet=Wallet::where(['user_id'=>$userId])->first();
        if(empty($wallet)){  
                $wallet=new Wallet();
                $wallet->user_id=$userId;
                $wallet->save();
              }
         $movementCount= Movement::where(['wallet_id'=>$wallet->id,'name'=>$name])->count();
         if($movementCount==0){
              $movementWallet=new Movement();
                $movementWallet->name=$name;
                $movementWallet->value=$points;
                $movementWallet->type=0;//Acquired
                $movementWallet->wallet_id=$wallet->id;
                $movementWallet->payment_id=null; 
                $movementWallet->remaining_wallet_points=$movementWallet->remaining_wallet_points+$points; 
                $movementWallet->save();

                $wallet->points=$wallet->points+$points;
                $wallet->save();
         }
    }
    
    public function updatePassword($request,$model){
        $userId=auth()->guard('api')->user()->id;
       $user= $model->where(['id'=>$userId])->first();
       $data=$request->validated();
            $loggedInPassword=   Storage::get($user->id.'-loggedInPassword');
            //check if oldPass = new pass 
                if($data['old_password']==$data['new_password']){
                    return 'لا يمكنك التعديل على كلمة سرك القديمة لان كلمة السر الجديدة التي ادخلتها مثل القديمة ';
                }
                if($loggedInPassword==$data['old_password']){
                    $newPassword=Hash::make($data['new_password']);
                    if($data['new_password']==$data['confirmation_new_password']){
                        $user->password=$newPassword;
                        $user->save();
                        return $user;
                    }else{
                        return 'كلمة المرور غير متطايقة مع تاكيد كلمة المرور';
                    }
                }else{
                    return 'كلمة المرور القديمة غير صحيحة , الرجاء المحاولة مرة اخرى';
                }

    }


    public function requestDocumentation($model,$userId){
      $user=  $model->find($userId);
      if($user->documentation===1){
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
            return 'طلبك بالطبع تم قبوله';
        }else{
            $user->documentaion=2;//request documentation has been accepted
            $user->save();
            return $user;
        }
    }
}