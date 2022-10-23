<?php

namespace Modules\Search\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Search\Entities\Search;
use Modules\Search\Http\Requests\StoreSearchRequest;
use Modules\Search\Http\Requests\UpdateSearchRequest;
use Modules\Search\Http\Requests\DeleteSearchRequest;
use Modules\Search\Repositories\Admin\SearchRepository;

class SearchController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var SearchRepository
    */
    protected $searchRepo;
    /**
    * @var Search
    */
    protected $search;


    /**
    * SearchsController constructor.
    *
    * @param SearchRepository $searchs
    */
    public function __construct(BaseRepository $baseRepo, Search $search,SearchRepository $searchRepo)
    {
    $this->middleware(['permission:searches_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:searches_trash'])->only('trash');
    $this->middleware(['permission:searches_restore'])->only('restore');
    $this->middleware(['permission:searches_restore-all'])->only('restore-all');
    $this->middleware(['permission:searches_show'])->only('show');
    $this->middleware(['permission:searches_store'])->only('store');
    $this->middleware(['permission:searches_update'])->only('update');
    $this->middleware(['permission:searches_destroy'])->only('destroy');
    $this->middleware(['permission:searches_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->search = $search;
    $this->searchRepo = $searchRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $searchs=$this->searchRepo->all($this->search);
            if(is_string($searchs)){
            return response()->json(['status'=>false,'message'=>$searchs],400);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$searchs],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllPaginates(Request $request){
        try{
        $searchs=$this->searchRepo->getAllPaginates($this->search,$request);
                if(is_string($searchs)){
            return response()->json(['status'=>false,'message'=>$searchs],400);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$searchs],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
    $searchs=$this->searchRepo->trash($this->search,$request);
                if(is_string($searchs)){
            return response()->json(['status'=>false,'message'=>$searchs],400);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$searchs],200);
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
    public function store(StoreSearchRequest $request)
    {
        try{
        $search=$this->searchRepo->store($request,$this->search);
            if(is_string($search)){
            return response()->json(['status'=>false,'message'=>$search],400);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);
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
    $search=$this->searchRepo->find($id,$this->search);

        if(is_string($search)){
            return response()->json(['status'=>false,'message'=>$search],404);
        }
   
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);
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
    public function update(UpdateSearchRequest $request,$id)
    {
        try{
    $search= $this->searchRepo->update($request,$id,$this->search);

    if(is_string($search)){
            return response()->json(['status'=>false,'message'=>$search],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }

   

    //methods for restoring
    public function restore($id){
        try{
    $search =  $this->searchRepo->restore($id,$this->search);
    
     if(is_string($search)){
            return response()->json(['status'=>false,'message'=>$search],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
        try{
            $searchs =  $this->searchRepo->restoreAll($this->search);
            
             if(is_string($searchs)){
                    return response()->json(['status'=>false,'message'=>$searchs],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$searchs],200);
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
    public function destroy(DeleteSearchRequest $request,$id)
    {
        try{
    $search= $this->searchRepo->destroy($id,$this->search);
     if(is_string($search)){
            return response()->json(['status'=>false,'message'=>$search],404);
        }
  
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    public function forceDelete(DeleteSearchRequest $request,$id)
    {
        try{
    //to make force destroy for a Search must be this Search  not found in Searchs table  , must be found in trash Searchs
    $search=$this->searchRepo->forceDelete($id,$this->search);
     if(is_string($search)){
            return response()->json(['status'=>false,'message'=>$search],404);
        }

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$search],200);
        }catch(\Exception $ex){
             return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
    
    
   
}
