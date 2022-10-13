<?php
namespace Modules\Favorite\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Favorite\Repositories\User\FavoriteRepositoryInterface;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Repositories\BaseRepository;
use Modules\Product\Entities\Product;
class FavoriteRepository extends EloquentRepository implements FavoriteRepositoryInterface
{
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
        ///////////////
    public function myFavorites($model){
        $user=auth()->guard('api')->user();
       $favorites= $model->where(['user_id'=>$user->id])->with(['product','product.productImages'])->paginate(5);
       if(count($favorites)==0){
           return 'لا يوجد اي منتج في مفضلاتك ';
       }
       $location = geoip(request()->ip());
       if($location->currency!==config('constants.currency_system')){
            foreach($favorites as $favorite){
                //convert this price that in dinar into currency user
                $favorite->currency_country=$this->baseRepo->countryCurrency();
                
                $convertingOriginalPriceAttr =  $this->baseRepo->priceCalculation($favorite->product->original_price);
                $favorite->product->original_price=$convertingOriginalPriceAttr;
                
                $convertingPriceEndsAttr =  $this->baseRepo->priceCalculation($favorite->product->price_discount_ends);
                $favorite->product->price_discount_ends=$convertingPriceEndsAttr;
                
            }
        }
       return $favorites;
        
    }
    
    public function addToFavorite($model,$productId){
        $user=auth()->user();
        $product=Product::where('id',$productId)->first();
        if(empty($product)){
            return 'غير موجود هذا المنتج لاضافته الى مفضلتك';
        }
        $favoriteSame=$model->where(['user_id'=>$user->id,'product_id'=>$productId])->first();
        if(!empty($favoriteSame)){
            return 'لا يمكن اضافة هذا المنتج مرة اخرى الى مفضلاتك';
        }
    //   $favorite= $model->create(['user_id'=>$user->id,'product_id'=>$productId]);
       $favorite=new $model;
       $favorite->user_id=$user->id;
       $favorite->product_id=$productId;
       $favorite->save();
       return $favorite;
    }

    public function removeFromFavorite($model,$id){
        $favorite=$model->where(['id'=>$id])->first();
        if(empty($favorite)){
            return 'هذا المنتج بالطبع تم ازالته من مفضلتك';
        }else{
           $favorite->delete();
            return $favorite;
        }
    }
    
    
}
