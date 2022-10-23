<?php
namespace Modules\Seo\Repositories;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Seo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Seo\Repositories\SeoRepositoryInterface;

class SeoRepository extends EloquentRepository implements SeoRepositoryInterface
{


    // methods overrides
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=Session::get('applocale');

        $enteredData=  Arr::except($data ,['seo_images']);

        $seo= $model->create($enteredData);

            if($request->hasFile('seo_images')){
                $filesseo=[];
                $files= $request->file('seo_images'); //upload file 
                foreach($files as $file){
                    $file_path_original= MediaClass::store($file,'seo-images');//store seo images
                    $data['seo_images']=$file_path_original;
                    array_push($filesseo,['filename'=>$file_path_original]);
                }
                $seo->seoImages()->createMany($filesseo);
            }
            return $seo;
    }
        public function update($request,$id,$model){

        $seo=$this->find($id,$model);
        if(!empty($seo)){
            
            $data= $request->validated();
    
            $enteredData=  Arr::except($data ,['seo_images']);
            $seo->update($enteredData);
            
        if($request->hasFile('seo_images')){

            $files= $request->file('seo_images'); //upload file 
           // dd($files);
            $filesseo=[];
            foreach($files as $file){ 

                $file_path_original= MediaClass::store($file,'seo-images');//store seo images
              array_push($filesseo,['filename'=>$file_path_original]);
              if($seo->seoImages->count()!==0){
                    foreach($seo->seoImages as $seoImage){
                        if($seoImage->filename!==$file_path_original){
                            $seo->seoImages()->delete();
                        }
                    }   
                }
            }
             $seo->seoImages()->createMany($filesseo);
 
 
        }

        }    

        return $seo;
        }

    
}
