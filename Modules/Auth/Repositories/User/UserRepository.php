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

class UserRepository extends EloquentRepository implements UserRepositoryInterface
// class UserRepository implements UserRepositoryInterface
{
     public function search($model,$words){
   // $model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->paginate(9);
    $modelData=$model->where(function ($query) use ($words) {
              $query->where('first_name', 'like', '%' . $words . '%');
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
        // dd(json_decode($data['roles']));
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

            return $user;
    }
        public function update($request,$id,$model){

        $user=$this->find($id,$model);
        if(!empty($category)){
        $data= $request->validated();
        $password=Str::random(8);
        $data['password']=Hash::make($password);

        $enteredData=  Arr::except($data ,['image']);
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
            $user->syncRoles($data['roles']);//to update roles a user
        }
    }
        return $user;
    }
    public function forceDelete($id,$model){
        //to make force destroy for an item must be this item  not found in items table  , must be found in trash items
        $itemInTableitems = $this->find($id,$model);//find this item from  table items
        if(empty($itemInTableitems)){//this item not found in items table
            $itemInTrash= $this->findItemOnlyTrashed($id,$model);//find this item from trash 
            if(empty($itemInTrash)){//this item not found in trash items
//   return __('this item  found in system so you cannt   delete it by forcely , you can delete it Temporarily after that delete it by forcely');
            
            return 'هذا العنصر  غير موجود بسلة المحذوفات لذلك يمكنك حذفه من النظام بالبداية وبعد ذلك حذفه من سلة المحذوفات  ';

         }else{
                $itemInTrash->detachRoles($itemInTrash->roles);
                // MediaClass::delete($itemInTrash->image);
                // MediaClass::delete($itemInTrash->passportImages);
                // $itemInTrash->passportImages()->delete();
                $itemInTrash->forceDelete();
                
                return $itemInTableitems;
            }
        }else{
            
            // return __('not found');
            return 'غير موجود بالنظام ';
                  
          }


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
