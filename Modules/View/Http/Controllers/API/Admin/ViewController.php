<?php

namespace Modules\View\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\View\Entities\View;
use Modules\View\Http\Requests\StoreViewRequest;
use Modules\View\Http\Requests\UpdateViewRequest;
use Modules\View\Http\Requests\DeleteViewRequest;
use Modules\View\Repositories\Admin\ViewRepository;

class ViewController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var ViewRepository
    */
    protected $viewRepo;
    /**
    * @var View
    */
    protected $view;


    /**
    * ViewsController constructor.
    *
    * @param ViewRepository $views
    */
    public function __construct(BaseRepository $baseRepo, View $view,ViewRepository $viewRepo)
    {
    $this->middleware(['permission:views_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:views_trash'])->only('trash');
    $this->middleware(['permission:views_restore'])->only('restore');
    $this->middleware(['permission:views_restore-all'])->only('restore-all');
    $this->middleware(['permission:views_show'])->only('show');
    $this->middleware(['permission:views_store'])->only('store');
    $this->middleware(['permission:views_update'])->only('update');
    $this->middleware(['permission:views_destroy'])->only('destroy');
    $this->middleware(['permission:views_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->view = $view;
    $this->viewRepo = $viewRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $views=$this->viewRepo->all($this->view);
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$views],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
    $views=$this->viewRepo->getAllPaginates($this->view,$request);
                 return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$views],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
  public function countData(){
        $countData=$this->viewRepo->countData($this->view);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$countData],200);
          
     }



    // methods for trash
    public function trash(Request $request){
        try{
             $views=$this->viewRepo->trash($this->view,$request);
        if(is_string($views)){
            return response()->json(['status'=>false,'message'=>$views],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$views],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreViewRequest $request)
    {
        try{
        $view=$this->viewRepo->store($request,$this->view);
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        try{
            $view=$this->viewRepo->find($id,$this->view);
    
        if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
   
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateViewRequest $request,$id)
    {
        try{
    $view= $this->viewRepo->update($request,$id,$this->view);
    if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
   
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }

  

    //methods for restoring
    public function restore($id){
        try{
            $view =  $this->viewRepo->restore($id,$this->view);
             if(is_string($view)){
                    return response()->json(['status'=>false,'message'=>$view],404);
                }
   
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        

    }
    public function restoreAll(){
        try{
    $views =  $this->viewRepo->restoreAll($this->view);
     if(is_string($views)){
            return response()->json(['status'=>false,'message'=>$views],404);
        }
        
          
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$views],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteViewRequest $request,$id)
    {
        try{
    $view= $this->viewRepo->destroy($id,$this->view);
     if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
          
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    public function forceDelete(DeleteViewRequest $request,$id)
    {
        try{
    //to make force destroy for a View must be this View  not found in Views table  , must be found in trash Views
    $view=$this->viewRepo->forceDelete($id,$this->view);
     if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }

          
         return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    
    
   
}
