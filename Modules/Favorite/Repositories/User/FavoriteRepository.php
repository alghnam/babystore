<?php
namespace Modules\Favorite\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Favorite\Repositories\User\FavoriteRepositoryInterface;
class FavoriteRepository extends EloquentRepository implements FavoriteRepositoryInterface
{

        ///////////////
    public function myFavorites($model){
              $user=auth()->guard('api')->user();
           $favorites= $model->where(['user_id'=>$user->id])->with(['product','product.productImages'])->paginate(5);
           if(count($favorites)==0){
            //   return __('not found any favorite for you');
               return 'لا يوجد اي منتج في مفضلاتك ';
           }
           return $favorites;
        
    }
    
    public function addToFavorite($model,$productId){
        $user=auth()->user();
        $favoriteSame=$model->where(['user_id'=>$user->id,'product_id'=>$productId])->first();
       // dd($favoriteSame);
        if(!empty($favoriteSame)){
            // return __('cannt add this product again , because you added it into your favorite in prev. time');
            return 'لا يمكن اضافة هذا المنتج مرة اخرى الى مفضلاتك';
        }
        
       $favorite= $model->create(['user_id'=>$user->id,'product_id'=>$productId]);
      // dd($favorite);
       return $favorite;
    }

    public function removeFromFavorite($model,$id){
        $favorite=$model->where(['id'=>$id])->first();
        if(empty($favorite)){
            // return __('this favorite already removed in prev. time');
            return 'هذا المنتج بالطبع تم ازالته من مفضلتك';
        }else{
                    $favorite->delete();
            return $favorite;
        }
    }
    
    
}
