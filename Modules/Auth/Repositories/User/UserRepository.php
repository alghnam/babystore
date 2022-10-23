<?php
namespace Modules\Auth\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\User;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Scopes\ActiveScope;
use App\Repositories\Auth\Sms\SmsRepository;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
            /**
     * @var SmsRepository
     */
    protected $smsRepo;
    public function __construct(SmsRepository $smsRepo){
        $this->smsRepo = $smsRepo;
    }
    
     public function search($model,$words){
    $modelData=$model->where(function ($query) use ($words) {
              $query->where('phone_no', 'like', '%' . $words . '%');
         })->get();
       return  $modelData;
   
    }

    public function countryUser($user){
        $countryUser= $user->profile->country;
        return $countryUser;
    }
    public function cityUser($user){
        $cityUser= $user->profile->city;
        return $cityUser;
    }
    public function townUser($user){
        $townUser= $user->profile->town;
        return $townUser;
    }


    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
                $data['locale']=config('app.locale');   

        $password=Str::random(8);
        $data['password']=Hash::make($password);

        
        $enteredData=  Arr::except($data ,['image']);

        $user= $model->create($enteredData);
        if(!empty($data['roles'])){
            $user->roles()->attach(json_decode($data['roles']));//to create roles for a user
        }


            if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original_image_user= MediaClass::store($request->file('image'),'profile-images');//store profile image
                    $data['image']=$file_path_original_image_user;
                }else{
                    $data['image']=$user->image;
                }
                $user->image()->create(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'App\Models\User']);
            }
            // Send sms to phone
            // $this->smsRepo->send($password,$user->phone_no);
            return $user;
    }
        public function update($request,$id,$model){

        $user=$this->find($id,$model);
        if(!empty($user)){
        $data= $request->validated();
        $password=Str::random(8);
        $data['password']=Hash::make($password);

        $enteredData=  Arr::except($data ,['image']);
                // dd($enteredData);

        $user->update($enteredData);
        


     if(!empty($data['image'])){
           if($request->hasFile('image')){
               $file_path_original= MediaClass::store($request->file('image'),'profile-images');//store profile image
               $data['image']=$file_path_original;

           }else{
               $data['image']=$user->image;
           }
         if($user->image){
            //   dd($data['image']);
             $user->image()->update(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'App\Models\User']);
   
         }else{
   
             $user->image()->create(['url'=>$data['image'],'imageable_id'=>$user->id,'imageable_type'=>'App\Models\User']);
         }
     }

        if(!empty($data['roles'])){
            $user->syncRoles(json_decode($data['roles']));//to update roles a user
        }
    }
        return $user;
    }


    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        // dd($itemInTableitems);
        if(is_string($itemInTableitems)){//this item not found in items table
            return $itemInTableitems;
        }
        
        $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
        if(is_string($itemInTrash)){//this item not found in trash items table
            return 'هذا العنصر غير موجود في سلة المحذوفات';
        }
            $itemInTrash->detachRoles($itemInTrash->roles);
            $itemInTrash->forceDelete();

            return 200;
    }
    
    //     public function restorePasswordUser($id,$model){
    //             //to make restorePasswordUser for an item must be this item  not found in items table  , must be found in trash items
    //     $itemInTableitems = $this->find($id,$model);//find this item from  table items
    //     if(!empty($itemInTableitems)){//this item not found in items table
    //             $password=Str::random(8);
                
    //      $hahedPassword=Hash::make($password);
    //     Storage::put('password',$password);

    //         $itemInTableitems->password=$hahedPassword;
    //         $itemInTableitems->save();
    //                 // Send sms to phone
    //   // $this->smsRepo->send($password,$itemInTableitems->phone_no);
                
    //             return $itemInTableitems;
            
    //     }else{

    //         // return __('not found');
    //         return 'غير موجود بالنظام';
    //         }
    // }

}
