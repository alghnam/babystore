<?php
namespace Modules\Question\Repositories\Admin\Categories;

use App\Repositories\EloquentRepository;
use Illuminate\Support\Arr;
use App\GeneralClasses\MediaClass;
use Image;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
     public function getAllPaginates($model,$request){
        $modelData=$model->with(['image'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
    
        // methods overrides
    public function store($request,$model){
        $data=$request->validated();
        $data['locale']=config('app.locale');

        
        $enteredData=  Arr::except($data ,['image']);

        $Category= $model->create($enteredData);



            if(!empty($data['image'])){
                if($request->hasFile('image')){
                    $file_path_original= MediaClass::store($request->file('image'),'questions-categories-images');//store Category image
                    
                    $data['image']=$file_path_original;

                }else{
                    $data['image']=$Category->image;
                }
                $Category->image()->create(['url'=>$data['image'],'imageable_id'=>$Category->id,'imageable_type'=>'Modules\Question\Entities\QuestionCategory']);
            }
            return $Category;
    }
        public function update($request,$id,$model){
        $Category=$this->find($id,$model);
        if(!empty($Category)){
            
            $data= $request->all();
    
            $enteredData=  Arr::except($data ,['image']);
            $Category->update($enteredData);
            
    
    
         if(!empty($data['image'])){
               if($request->hasFile('image')){
                   $file_path_original= MediaClass::store($request->file('image'),'questions-categories-images');//store Category image
                                                                           $file_path_original_without_public= str_replace("public/","",$file_path_original);

                $data['image']=$file_path_original_without_public;

               }else{
                   $data['image']=$Category->image;
               }
             if($Category->image){
                //   dd($data['image']);
                 $Category->image()->update(['url'=>$data['image'],'imageable_id'=>$Category->id,'imageable_type'=>'Modules\Question\Entities\QuestionCategory']);
       
             }else{
       
                 $Category->image()->create(['url'=>$data['image'],'imageable_id'=>$Category->id,'imageable_type'=>'Modules\Question\Entities\QuestionCategory']);
             }
         }

        }    

        return $Category;
    }
}
