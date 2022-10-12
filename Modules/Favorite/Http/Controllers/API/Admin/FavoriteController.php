<?php

namespace Modules\Favorite\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Favorite\Http\Requests\DeleteFavoriteRequest;
use Modules\Favorite\Http\Requests\StoreFavoriteRequest;
use Modules\Favorite\Http\Requests\UpdateFavoriteRequest;
use Modules\Favorite\Repositories\Admin\FavoriteRepository;
use  Modules\Favorite\Entities\Favorite;
use Modules\Favorite\Http\Requests\AddToFavoriteRequest;

class FavoriteController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
    /**
     * @var FavoriteRepository
     */
    protected $favoriteRepo;
    /**
     * @var Favorite
     */
    protected $favorite;
   

    /**
     * FavoritesController constructor.
     *
     * @param FavoriteRepository $favorites
     */
    public function __construct(BaseRepository $baseRepo, Favorite $favorite,FavoriteRepository $favoriteRepo)
    {
        $this->middleware(['permission:favorites_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:favorites_trash'])->only('trash');
        $this->middleware(['permission:favorites_restore'])->only('restore');
        $this->middleware(['permission:favorites_restore-all'])->only('restore-all');
        $this->middleware(['permission:favorites_show'])->only('show');
        $this->middleware(['permission:favorites_store'])->only('store');
        $this->middleware(['permission:favorites_update'])->only('update');
        $this->middleware(['permission:favorites_destroy'])->only('destroy');
        $this->middleware(['permission:favorites_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->favorite = $favorite;
        $this->favoriteRepo = $favoriteRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $favorites=$this->favoriteRepo->all($this->favorite);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorites],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
         try{
        $favorites=$this->favoriteRepo->getAllPaginates($this->favorite,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorites],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getFavoritesProduct(Request $request,$productId){
        
         try{
        $favorites=$this->favoriteRepo->getFavoritesProduct($this->favorite,$request,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorites],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
        try{
            $favorites=$this->favoriteRepo->trash($this->favorite,$request);
            if(is_string($favorites)){
                return response()->json(['status'=>false,'message'=>$favorites],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorites],200);

        
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
    public function store(StoreFavoriteRequest $request)
    {
         try{
       $favorite= $this->favoriteRepo->store($request,$this->favorite);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);

        
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
            $favorite=$this->favoriteRepo->find($id,$this->favorite);
            if(is_string($favorite)){
                return response()->json(['status'=>false,'message'=>$favorite],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);

        
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
    public function update(UpdateFavoriteRequest $request,$id)
    {
          try{
            $favorite= $this->favoriteRepo->update($request,$id,$this->favorite);
            if(is_string($favorite)){
                return response()->json(['status'=>false,'message'=>$favorite],404);
            }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
            $favorite =  $this->favoriteRepo->restore($id,$this->favorite);
             if(is_string($favorite)){
                 return response()->json(['status'=>false,'message'=>$favorite],404);
                }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
        $favorites =  $this->favoriteRepo->restoreAll($this->favorite);
             if(is_string($favorites)){
            return response()->json(['status'=>false,'message'=>$favorites],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorites],200);

        
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
    public function destroy(DeleteFavoriteRequest $request,$id)
    {
          try{
            $favorite= $this->favoriteRepo->destroy($id,$this->favorite);
                if(is_string($favorite)){
            return response()->json(['status'=>false,'message'=>$favorite],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteFavoriteRequest $request,$id)
    {
          try{
            //to make force destroy for a Favorite must be this Favorite  not found in Favorites table  , must be found in trash Favorites
            $favorite=$this->favoriteRepo->forceDelete($id,$this->favorite);
              if(is_string($favorite)){
                    return response()->json(['status'=>false,'message'=>$favorite],404);
                }
                return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    


 
}
