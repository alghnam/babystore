<?php

namespace Modules\Favorite\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Favorite\Http\Requests\DeleteFavoriteRequest;
use Modules\Favorite\Http\Requests\StoreFavoriteRequest;
use Modules\Favorite\Http\Requests\UpdateFavoriteRequest;
use Modules\Favorite\Repositories\User\FavoriteRepository;
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
        $this->middleware(['permission:favorites_get'])->only('myFavorites');
        $this->middleware(['permission:favorites_add'])->only('AddToFavorite');
        $this->middleware(['permission:favorites_remove'])->only('removeFromFavorite');
        $this->baseRepo = $baseRepo;
        $this->favorite = $favorite;
        $this->favoriteRepo = $favoriteRepo;
    }
    ///////////
public function myFavorites(){
    try{
    $myFavorites=$this->favoriteRepo->myFavorites($this->favorite);
                if(is_string($myFavorites)){
            return response()->json(['status'=>false,'message'=>$myFavorites],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$myFavorites],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
 
}

public function AddToFavorite($id){
    // try{
    $favorite=$this->favoriteRepo->AddToFavorite($this->favorite,$id);
                if(is_string($favorite)){
            return response()->json(['status'=>false,'message'=>$favorite],400);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);
            
                
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    
       
}

public function removeFromFavorite($id){
    try{
    $favorite=$this->favoriteRepo->removeFromFavorite($this->favorite,$id);
                if(is_string($favorite)){
            return response()->json(['status'=>false,'message'=>$favorite],404);
        }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$favorite],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
    }
       


 
}
