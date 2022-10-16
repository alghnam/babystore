<?php
namespace Modules\Product\Repositories\User;

use Modules\Product\Entities\ProductImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;
use Modules\Product\Repositories\User\ProductRepositoryInterface;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use Modules\Product\Entities\Product;
use DB;
use Stevebauman\Location\Facades\Location;
use Modules\Order\Entities\Order;
use Modules\Category\Entities\Category;
use Modules\Search\Entities\Search;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Repositories\BaseRepository;
class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    public function getLocation(){
        $ip=request()->ip();
       $location= \Location::get($ip);
       $longitude=$location->longitude;
       $latitude=$location->latitude;
       $data=[
           'longitude'=>$longitude,
           'latitude'=>$latitude,
           ];
           return $data;

    }

 public function search($model,$words,$request){
            $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);
                       
                         $modelData=$model->where(function ($query) use ($words) {
                              $query->where('name', 'like', '%' . $words . '%');
                         })->with(['productImages'])->paginate($request->total);
                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                     
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);
                        $modelData=$model->where(function ($query) use ($words) {
                        $query->where('name', 'like', '%' . $words . '%');
                        })->with(['productImages'])->paginate($request->total);
                        
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);
                    
                    $modelData=$model->where(function ($query) use ($words) {
                    $query->where('name', 'like', '%' . $words . '%');
                     })->with(['productImages','favorites'=> function ($hasMany) {
                        $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    }])->paginate($request->total);
                }
            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }
            //   dd($modelData);
            
            }

       return  $modelData;
   
    }

        public function searchMoreSale($model,$words,$request){
            $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);
                  $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')
                        ->withCount(['reviews as reviews_avg' => function($query) {
                        $query->select(DB::raw('avg(rating)'));
                    }])->with('productImages')->take(10)->paginate($request->total);
                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);
  $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')->with(['productImages','favorites'=> function ($hasMany) {
       $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])
        ->withCount(['reviews as reviews_avg' => function($query) {
        $query->select(DB::raw('avg(rating)'));
    }])->take(10)->paginate($request->total);
                }
            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }

               return  $modelData;
           
            } 
        public function searchModern($model,$words,$request){
                        $user=auth()->guard('api')->user();
                if($user==null){
                    $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->orderBy('created_at', 'desc')->with(['productImages'])->paginate(8);
                    
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    $modelData=$model->where(function ($query) use ($words) {
                      $query->where('name', 'like', '%' . $words . '%');
                 })->orderBy('created_at', 'desc')->with(['productImages','favorites'=> function ($hasMany) {
                $hasMany->where('user_id', auth()->guard('api')->user()->id);
            }])->paginate(8);
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                }
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }

            return  $modelData;
        
        }   
        public function searchOffers($model,$words,$request){
                        $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                            $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->where('is_offers',1)->with(['productImages'])->paginate($request->total);
                    
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);
                $modelData=$model->where(function ($query) use ($words) {
                      $query->where('name', 'like', '%' . $words . '%');
                 })->where('is_offers',1)->with(['productImages','favorites'=> function ($hasMany) {
                $hasMany->where('user_id', auth()->guard('api')->user()->id);
            }])->paginate($request->total);
                }
            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }

            return  $modelData;
        
        }
        public function searchProductsSpesificPriceSpesificWord($model,$words,$price1,$price2,$request){
                        $user=auth()->guard('api')->user();
                if($user==null){
                        $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages'])->paginate($request->total);
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                    $modelData=$model->where(function ($query) use ($words) {
                              $query->where('name', 'like', '%' . $words . '%');
                         })->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages','favorites'=> function ($hasMany) {
                        $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    }])->paginate($request->total);
                }
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }

       return  $modelData;
   
    }
    
    public function searchPriceMoreSale($model,$words,$price1,$price2,$request){
                    $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                     $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')
                        ->withCount(['reviews as reviews_avg' => function($query) {
                        $query->select(DB::raw('avg(rating)'));
                    }])->with('productImages')->take(10)->paginate($request->total);
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                  $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')->with(['productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])
                    ->withCount(['reviews as reviews_avg' => function($query) {
                    $query->select(DB::raw('avg(rating)'));
                }])->take(10)->paginate($request->total);
                }
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }

           return  $modelData;
       
        }
    public function searchPriceModern($model,$words,$price1,$price2,$request){
                    $user=auth()->guard('api')->user();
                if($user==null){
                     $modelData=$model->where(function ($query) use ($words) {
                           $query->where('name', 'like', '%' . $words . '%');
                      })->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages'])->orderBy('created_at', 'desc')->paginate(10);
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                     $modelData=$model->where(function ($query) use ($words) {
                           $query->where('name', 'like', '%' . $words . '%');
                      })->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])->orderBy('created_at', 'desc')->paginate(10);
                }
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }

            return  $modelData;
        
        }
    public function searchPriceOffers($model,$words,$price1,$price2,$request){
                    $user=auth()->guard('api')->user();
                if($user==null){
                      $modelData=$model->where(function ($query) use ($words) {
                          $query->where('name', 'like', '%' . $words . '%');
                     })->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages'])->where('is_offers',1)->paginate($request->total);
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                    $modelData=$model->where(function ($query) use ($words) {
                          $query->where('name', 'like', '%' . $words . '%');
                     })->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])->where('is_offers',1)->paginate($request->total);
                }
                
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }
            return  $modelData;
        
        }



    public function searchProductsSpesificCategorySpesificWord($model,$categoryId,$words,$request){
                    $user=auth()->guard('api')->user();
                if($user==null){
                    $modelData=$model->where(function ($query) use ($words) {
                          $query->where('name', 'like', '%' . $words . '%');
                     })->where(['category_id'=>$categoryId])->with(['productImages'])->paginate($request->total);
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                    $modelData=$model->where(function ($query) use ($words) {
                          $query->where('name', 'like', '%' . $words . '%');
                     })->where(['category_id'=>$categoryId])->with(['productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])->paginate($request->total);
                }
                
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }
           return  $modelData;
       
        }

        public function searchProductsSpesificCategorySpesificWordOrderMoreSale($model,$categoryId,$search,$request){
                        $user=auth()->guard('api')->user();
                if($user==null){
                                $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->with('productImages')->paginate($request->total);
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                    $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->with(['productImages','favorites'=> function ($hasMany) {
                        $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    }])->paginate($request->total);
                }
                
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }
               return  $modelData;
           
            }
            public function searchProductsSpesificCategorySpesificWordOrderModern($model,$categoryId,$search,$request){
                            $user=auth()->guard('api')->user();
                if($user==null){
                                    $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->orderBy('created_at', 'desc')->with('productImages')->paginate(10);
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                }else{
                    $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->orderBy('created_at', 'desc')->with(['productImages','favorites'=> function ($hasMany) {
                        $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    }])->paginate(10);
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);

                }

                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }
                   return  $modelData;
               
                }
                public function searchProductsSpesificCategorySpesificWordOrderOffers($model,$categoryId,$search,$request){
                                $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                                       $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->with('productImages')->paginate($request->total);
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);
                   $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->with(['productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])->paginate($request->total);
                            
                    
                }
 
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }
                       return  $modelData;

                    }




        public function searchProductsSpesificCategoryAndPriceSpesificWord($model,$categoryId,$price1,$price2,$search,$request){
                        $user=auth()->guard('api')->user();
                if($user==null){
                    //generate session id
                    $session_id=Storage::get('session_id');
                    if(empty($session_id)){
                        $session_id= Str::random(30);
                        Storage::put('session_id',$session_id);
                        Search::insert(['word'=>$words,'session_id'=>$session_id]);

                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                    }
                                $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->whereBetween('price_discount_ends',[$price1,$price2])->with('productImages')->paginate($request->total);

                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);
                        $modelData=$model->where('name', 'like', '%' . str_slug($search, ' ') . '%')->where(['category_id'=>$categoryId])->whereBetween('price_discount_ends',[$price1,$price2])->with(['productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])->paginate($request->total);

                }
                
                            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                foreach($modelData as $pro){
                    $pro->currency_country=$this->baseRepo->countryCurrency();
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                    foreach($pro->productArrayAttributes as $attr){
                         //convert this price that in dinar into currency user
                        if($attr->original_price){
                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                            $attr->original_price=$convertingOriginalPrice;
                        }
                        if($attr->price_discount_ends){
                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                            $attr->price_discount_ends=$convertingPriceEnds;
                        }
                    }
                }
                    
                }

            }
               return  $modelData;
           
            }

    public function showProductWithRelations($model,$id){

        $item=  $model->where(['id'=>$id])
                ->withCount(['reviews as reviews_avg' => function($query) {
                $query->select(DB::raw('avg(rating)'));
                    }])->first();
                if(auth()->guard('api')->user()==null){
                        if(!empty($item)){
                            $item->load(['category.mainCategory','category','productAttributes','productAttributes.image','productArrayAttributes','productImages','reviews.user.image','similarProducts.similar.productImages']);
                            $location = geoip(request()->ip());
                            if($location->currency!==config('constants.currency_system')){
                                //convert this price that in dinar into currency user
                                $item->currency_country=$this->baseRepo->countryCurrency();

                                if($item->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->original_price);
                                            $item->original_price=$convertingOriginalPrice;
                                    
                                }
                                if($item->price_discount_ends){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->price_discount_ends);
                                            $item->price_discount_ends=$convertingOriginalPrice;
                                    
                                }
                               
                                if($item->productArrayAttributes){
                                    foreach($item->productArrayAttributes as $attr){
                                         //convert this price that in dinar into currency user
                                        $attr->currency_country=$this->baseRepo->countryCurrency();
                                        if($attr->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $attr->original_price=$convertingOriginalPrice;
                                        }
                                        if($attr->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $attr->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                                if($item->similarProducts){
                                    foreach($item->similarProducts as $simi){
                                        $pro=Product::where('id',$simi->similar)->first();
                                        
                                        
                                         //convert this price that in dinar into currency user
                                        if($pro->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $simi->original_price=$convertingOriginalPrice;
                                            
                                        }
                                        if($pro->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $pro->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                            
                            }
                            return $item;
                    }else{
                          $item=  $model->where(['id'=>$id])
                            ->withCount(['reviews as reviews_avg' => function($query) {
                            $query->select(DB::raw('avg(rating)'));
                        }])->first();
                            $location = geoip(request()->ip());
                            if($location->currency!==config('constants.currency_system')){
                                //convert this price that in dinar into currency user
                                $item->currency_country=$this->baseRepo->countryCurrency();

                                if($item->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->original_price);
                                            $item->original_price=$convertingOriginalPrice;
                                    
                                }
                                if($item->price_discount_ends){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->price_discount_ends);
                                            $item->price_discount_ends=$convertingOriginalPrice;
                                    
                                }
                               
                                if($item->productArrayAttributes){
                                    foreach($item->productArrayAttributes as $attr){
                                         //convert this price that in dinar into currency user
                                        $attr->currency_country=$this->baseRepo->countryCurrency();
                                        if($attr->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $attr->original_price=$convertingOriginalPrice;
                                        }
                                        if($attr->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $attr->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                                if($item->similarProducts){
                                    foreach($item->similarProducts as $simi){
                                        $pro=Product::where('id',$simi->similar)->first();
                                        
                                         //convert this price that in dinar into currency user
                                        if($pro->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $simi->original_price=$convertingOriginalPrice;
                                        }
                                        if($pro->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $simi->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                            
                            }
                            return $item;
                     
            
                    }
        
 
            }else{
                if(!empty($item)){
                    $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes','productAttributes.image','productImages','reviews.user.image','similarProducts.similar.productImages','similarProducts.similar.favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    },'favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    }]);
                            $location = geoip(request()->ip());
                            if($location->currency!==config('constants.currency_system')){
                                //convert this price that in dinar into currency user
                                $item->currency_country=$this->baseRepo->countryCurrency();

                                if($item->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->original_price);
                                            $item->original_price=$convertingOriginalPrice;
                                    
                                }
                                if($item->price_discount_ends){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->price_discount_ends);
                                            $item->price_discount_ends=$convertingOriginalPrice;
                                    
                                }
                               
                                if($item->productArrayAttributes){
                                    foreach($item->productArrayAttributes as $attr){
                                         //convert this price that in dinar into currency user
                                        $attr->currency_country=$this->baseRepo->countryCurrency();
                                        if($attr->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $attr->original_price=$convertingOriginalPrice;
                                        }
                                        if($attr->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $attr->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                                if($item->similarProducts){
                                    foreach($item->similarProducts as $simi){
                                        $pro=Product::where('id',$simi->similar)->first();
                                        
                                         //convert this price that in dinar into currency user
                                        if($pro->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $simi->original_price=$convertingOriginalPrice;
                                        }
                                        if($pro->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $simi->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                            
                            }
                            return $item;

        }else{
            $item=  $model->where(['id'=>$id])
                ->withCount(['reviews as reviews_avg' => function($query) {
                 $query->select(DB::raw('avg(rating)'));
             }])->first();
                            $location = geoip(request()->ip());
                            if($location->currency!==config('constants.currency_system')){
                                //convert this price that in dinar into currency user
                                $item->currency_country=$this->baseRepo->countryCurrency();

                                if($item->original_price){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->original_price);
                                            $item->original_price=$convertingOriginalPrice;
                                    
                                }
                                if($item->price_discount_ends){
                                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($item->price_discount_ends);
                                            $item->price_discount_ends=$convertingOriginalPrice;
                                    
                                }
                               
                                if($item->productArrayAttributes){
                                    foreach($item->productArrayAttributes as $attr){
                                         //convert this price that in dinar into currency user
                                        $attr->currency_country=$this->baseRepo->countryCurrency();
                                        if($attr->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $attr->original_price=$convertingOriginalPrice;
                                        }
                                        if($attr->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $attr->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                                if($item->similarProducts){
                                    foreach($item->similarProducts as $simi){
                                        $pro=Product::where('id',$simi->similar)->first();
                                        
                                         //convert this price that in dinar into currency user
                                        if($pro->original_price){
                                            $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                            $simi->original_price=$convertingOriginalPrice;
                                        }
                                        if($pro->price_discount_ends){
                                            $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                            $simi->price_discount_ends=$convertingPriceEnds;
                                        }
                                    }
                                }
                            
                            }
                            return $item;
    
        }
 
            }
    }
    public function showDetailsProductArrayAttribute($model,$id){
       $ProductArrayAttribute= $model->where(['id'=>$id])->first();
        if(!empty($ProductArrayAttribute)){
            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                //convert this price that in dinar into currency user
                $ProductArrayAttribute->currency_country=$this->baseRepo->countryCurrency();
                if($ProductArrayAttribute->original_price){
                    $convertingOriginalPrice =  $this->baseRepo->priceCalculation($ProductArrayAttribute->original_price);
                    $ProductArrayAttribute->original_price=$convertingOriginalPrice;
                    $data=[
                        'original price of the product'=>$convertingCurrencies,
                        'productArrayAttribute'=>$ProductArrayAttribute
                        ];
                }else{
                    $data=[
                'original price of the product'=>null,
                'productArrayAttribute'=>$ProductArrayAttribute
                ];
                }
               
            }else{
                 $data=[
                'productArrayAttribute'=>$ProductArrayAttribute
                ];
            }
            
          
                return $data;
        }else{
         return 'غير موجود';
     }
    }
    
    public function showAttributesProduct($model,$id){

     $product=   $model->where(['id'=>$id])->first();

     if($product){
         
     return $product->productAttributes;
     }else{
         //return __('not found');
         return 'غير موجود';
     }
    }
    public function getCartUser(){
        $userId=auth()->user()->id;
        if($userId==null){
            //generate session id
            $session_id=Session::get('session_id');
            if(empty($session_id)){
                $session_id=Session::getId();//will be this cart empty , because now created it
                Session::put('session_id',$session_id);
                //create a cart for this session that created now
                $cart=new Cart();
                $cart->session_id=$session_id;
                $cart->save();
                return $cart;
            }else{
                $cart=Cart::where('session_id',$session_id)->first();
                $cart->session_id=$cart->session_id;
                $cart->save();
                return $cart->load('products');
            }
        }else{
            $cart=Cart::where('user_id',$userId)->first();
            $cart->user_id=$cart->cart_id;
            $cart->save();
            return $cart->load('products'); 
        }
    }
        public function getProductsForCategory($model,$categoryId,$request){
            if(auth()->guard('api')->user()==null){

                $modelData=$model->where('category_id',$categoryId)->with(['category.mainCategory','productImages'])->paginate($request->total);
            }else{
                 $modelData=$model->where('category_id',$categoryId)->with(['category.mainCategory','productImages','favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                }])->paginate($request->total);

            }
                     $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($modelData as $pro){
                    //convert this price that in dinar into currency user
                    $pro->currency_country=$this->baseRepo->countryCurrency();
    
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                        foreach($pro->productArrayAttributes as $attr){
                             //convert this price that in dinar into currency user
                            $attr->currency_country=$this->baseRepo->countryCurrency();
                            if($attr->original_price){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                $attr->original_price=$convertingOriginalPrice;
                            }
                            if($attr->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                $attr->price_discount_ends=$convertingPriceEnds;
                            }
                        }
                    }
                }
            }
          return  $modelData;
    }

 

    
    public function find($id,$model){
      $item=  $model->find($id);
       $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes','productImages']);
        return $item;
    }  
   
    public function getMoreSaleProducts($model,$request){ 
            
        if(auth()->guard('api')->user()==null){

            $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')->with('productImages')
                ->withCount(['reviews as reviews_avg' => function($query) {
                $query->select(DB::raw('avg(rating)'));
            }])->paginate($request->total);
        }else{
          
            $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')->with(['productImages','favorites'=> function ($hasMany) {
                $hasMany->where('user_id', auth()->guard('api')->user()->id);
            }])->withCount(['reviews as reviews_avg' => function($query) {
                $query->select(DB::raw('avg(rating)'));
            }])->paginate($request->total); 
        }
         $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($modelData as $pro){
                    //convert this price that in dinar into currency user
                    $pro->currency_country=$this->baseRepo->countryCurrency();
    
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                        foreach($pro->productArrayAttributes as $attr){
                             //convert this price that in dinar into currency user
                            $attr->currency_country=$this->baseRepo->countryCurrency();
                            if($attr->original_price){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                $attr->original_price=$convertingOriginalPrice;
                            }
                            if($attr->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                $attr->price_discount_ends=$convertingPriceEnds;
                            }
                        }
                    }
                }
            }
            return $modelData;
           
    }
    public function getModernProducts($model,$request){
        if(auth()->guard('api')->user()==null){

            $modelData=$model->orderBy('created_at','desc')->with(['productImages'])
            ->withCount(['reviews as reviews_avg' => function($query) {
                $query->select(DB::raw('avg(rating)'));
            }])->paginate($request->total);
        
        }else{
         
            $modelData=$model->orderBy('created_at', 'desc')->with(['productImages','favorites'=> function ($hasMany) {
                $hasMany->where('user_id', auth()->guard('api')->user()->id);
            }])->withCount(['reviews as reviews_avg' => function($query) {
                $query->select(DB::raw('avg(rating)'));
            }])->paginate($request->total);
            
        }
         $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($modelData as $pro){
                    //convert this price that in dinar into currency user
                    $pro->currency_country=$this->baseRepo->countryCurrency();
    
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                        foreach($pro->productArrayAttributes as $attr){
                             //convert this price that in dinar into currency user
                            $attr->currency_country=$this->baseRepo->countryCurrency();
                            if($attr->original_price){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                $attr->original_price=$convertingOriginalPrice;
                            }
                            if($attr->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                $attr->price_discount_ends=$convertingPriceEnds;
                            }
                        }
                    }
                }
            }
       return  $modelData;
   
    }
    public function getOffersProducts($model,$request){
        
        if(auth()->guard('api')->user()==null){
            $modelData=$model->where('is_offers',1)->with('category.mainCategory')->with(['productImages','reviews'])->paginate($request->total);
        }else{
            
            $modelData=$model->where('is_offers',1)->with('category.mainCategory')->with(['productImages','reviews','favorites'=> function ($hasMany) {
                $hasMany->where('user_id', auth()->guard('api')->user()->id);
            }])->paginate($request->total);
        }
         $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($modelData as $pro){
                    //convert this price that in dinar into currency user
                    $pro->currency_country=$this->baseRepo->countryCurrency();
    
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                        foreach($pro->productArrayAttributes as $attr){
                             //convert this price that in dinar into currency user
                            $attr->currency_country=$this->baseRepo->countryCurrency();
                            if($attr->original_price){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                $attr->original_price=$convertingOriginalPrice;
                            }
                            if($attr->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                $attr->price_discount_ends=$convertingPriceEnds;
                            }
                        }
                    }
                }
            }
           return  $modelData;
       
        }
        
         public function getProductsForSubCategoryTable($model,$subCategoryId,$request){
            if(auth()->guard('api')->user()==null){

                $modelData=$model->where('sub_category_id',$subCategoryId)->with(['subCategory','productImages'])->paginate($request->total);
            }else{
            $modelData=$model->where('sub_category_id',$subCategoryId)->with(['subCategory','productImages','favorites'=> function ($hasMany) {
                $hasMany->where('user_id', auth()->guard('api')->user()->id);
            }])->paginate($request->total);
                        
        }
                 $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($modelData as $pro){
                    //convert this price that in dinar into currency user
                    $pro->currency_country=$this->baseRepo->countryCurrency();
    
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                    if($pro->productArrayAttributes){
                        foreach($pro->productArrayAttributes as $attr){
                             //convert this price that in dinar into currency user
                            $attr->currency_country=$this->baseRepo->countryCurrency();
                            if($attr->original_price){
                                $convertingOriginalPrice =  $this->baseRepo->priceCalculation($attr->original_price);
                                $attr->original_price=$convertingOriginalPrice;
                            }
                            if($attr->price_discount_ends){
                                $convertingPriceEnds =  $this->baseRepo->priceCalculation($attr->price_discount_ends);
                                $attr->price_discount_ends=$convertingPriceEnds;
                            }
                        }
                    }
                }
            }
          return  $modelData;
    } 

    // methods overrides

    public function showAttributeIdForArray(Request $request){
        // dd($request->attributes);
       $showAttributeIdForArray= ProductArrayAttribute::where(['attributes'=>$request->attributes])->first();
       if(empty($showAttributeIdForArray)){
           return 'غير موجود بالنظام , من فضلك حاول الاختيار مرة اخرى';
       }
        return $showAttributeIdForArray;
    }



    public function deleteImage($idImage){
        
       $image= ProductImage::find($idImage);
       $image->delete();
       return 200;
        
    }

    
}
