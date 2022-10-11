<?php

namespace Modules\Favorite\Http\Controllers\API\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
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
    $myFavorites=$this->favoriteRepo->myFavorites($this->favorite);
                if(is_string($myFavorites)){
            return response()->json(['status'=>false,'message'=>$myFavorites],404);
        }

     return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'favorite has been getten successfully',
            'data'=> $myFavorites
        ]);
 
}

public function AddToFavorite($id){
    $favorite=$this->favoriteRepo->AddToFavorite($this->favorite,$id);
                if(is_string($favorite)){
            return response()->json(['status'=>false,'message'=>$favorite],400);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'favorite has been added successfully',
            'data'=> $favorite->load(['product','user'])
        ]);
    
       
}

public function removeFromFavorite($id){
    $favorite=$this->favoriteRepo->removeFromFavorite($this->favorite,$id);
                if(is_string($favorite)){
            return response()->json(['status'=>false,'message'=>$favorite],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'favorite has been removed successfully',
            'data'=> null
        ]);
        
    }
       


 
}
