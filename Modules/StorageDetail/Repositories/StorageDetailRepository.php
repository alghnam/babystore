<?php
namespace Modules\StorageDetail\Repositories;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\StorageDetail\Repositories\StorageDetailRepositoryInterface;

class StorageDetailRepository extends EloquentRepository implements StorageDetailRepositoryInterface
{


    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=Session::get('applocale');

        

        $seo= $model->create($data);


            return $seo;
    }
        public function update($request,$id,$model){

        $seo=$this->find($id,$model);
        if(!empty($seo)){
            
            $data= $request->validated();
    
            $seo->update($data);
            

        }    

        return $seo;
        }

    
}
