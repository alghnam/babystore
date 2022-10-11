<?php
namespace Modules\Product\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use Modules\Product\Entities\ProductImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Entities\Cart;
use Modules\Product\Repositories\User\ProductRepositoryInterface;
use Modules\ProductAttribute\Entities\ProductArrayAttribute;
use Modules\Product\Entities\Product;
// use Location;
use DB;
use Stevebauman\Location\Facades\Location;

// use Adrianorosa\GeoLocation\GeoLocation;

use MakiDizajnerica\GeoLocation\Facades\GeoLocation;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Modules\Order\Entities\Order;
use Modules\Category\Entities\Category;
use Modules\Order\Entities\ProductOrder;
use Modules\Search\Entities\Search;
class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
// class ProductRepository extends EloquentRepository
{
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

 public function search($model,$words){
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
                                 })->with(['productImages'])->get();
                    }else{
                       Search::insert(['word'=>$words,'session_id'=>$session_id]);
                        $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->with(['productImages'])->get();
                        
                    }
                }else{
                    Search::insert(['word'=>$words,'user_id'=>$user->id]);
    $modelData=$model->where(function ($query) use ($words) {
              $query->where('name', 'like', '%' . $words . '%');
         })->with(['productImages','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])->get();
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

        }else{
              $item=  $model->where(['id'=>$id])
                ->withCount(['reviews as reviews_avg' => function($query) {
         $query->select(DB::raw('avg(rating)'));
     }])->first();


        }
 
        return $item;
                
            }else{
                if(!empty($item)){
                    $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes','productAttributes.image','productImages','reviews.user.image','similarProducts.similar.productImages','similarProducts.similar.favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    },'favorites'=> function ($hasMany) {
                    $hasMany->where('user_id', auth()->guard('api')->user()->id);
                    }]);

        }else{
            $item=  $model->where(['id'=>$id])
                ->withCount(['reviews as reviews_avg' => function($query) {
                 $query->select(DB::raw('avg(rating)'));
             }])->first();

    
        }
 
        return $item;
            }
    }
    public function showDetailsProductArrayAttribute($model,$id){
       $ProductArrayAttribute= $model->where(['id'=>$id])->first();
        if(!empty($ProductArrayAttribute)){

            $userIp = request()->ip();
                        $location = geoip($userIp);
    
            //convert this price that in dinar into currency user
            $currencySystem='KWD';
            $currencyCountry=$location->currency;
            if($ProductArrayAttribute->original_price){
                
              $convertingCurrencies=  Currency::convert()
            ->from($currencySystem)
            ->to($currencyCountry)
            ->amount($ProductArrayAttribute->original_price)
            ->get();
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
                return $data;
        }else{
         //return __('not found');
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
        public function getProductsForCategory($model,$categoryId){
                if(auth()->guard('api')->user()==null){

        $modelData=$model->where('category_id',$categoryId)->with(['category.mainCategory','productImages'])->get();
                }else{
                            $modelData=$model->where('category_id',$categoryId)->with(['category.mainCategory','productImages','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])->get();

                }
          return  $modelData;
    }

 

    
    public function find($id,$model){
      $item=  $model->find($id);
       $item->load(['category.mainCategory','category','productAttributes','productArrayAttributes','productImages']);
        return $item;
    }  
   
    public function getMoreSaleProducts($model,$request){ 
            
//in orm 
        if(auth()->guard('api')->user()==null){

  $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')->with('productImages')
        ->withCount(['reviews as reviews_avg' => function($query) {
        $query->select(DB::raw('avg(rating)'));
    }])->take(10)->paginate($request->total);
        }else{
           $modelData=  $model->where('orders_counter','!=',0)->orderBy('orders_counter','desc')->with(['productImages','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])
        ->withCount(['reviews as reviews_avg' => function($query) {
        $query->select(DB::raw('avg(rating)'));
    }])->take(10)->paginate($request->total);   
        }

            return $modelData;

  //يعني من جدول الربط بين الاوردرز والبرودكتز هاتلي اكتر المنتجات الموجودة بالاوردرات 
  //يعني بنطلع بعمود البرودكت ايدي مين ايدي البرودكت متكرر اكتر واحد هو المبيوع اكتر 
     $pro=$model->with('orders');
     //بجدول المنتجات نحط عمود لعد المنتج كم مرة بنطلب متلا عند م اعمل اوردر ع منتج معين او عدة منتجات حروح ع كل واحد فيهم بهادا الاوردر حروح ازود العداد تاع المنتج هادا 
     //فعشان اجيب الاكتر مبيعا حجيب بشكل تنازلي المنتجات حسب عدي لعمود العداد عشان يجيب الاكتر مبيعا 
     
      $products=  $model->withCount(['orders' => function ($query) {
        $query->orderBy('products_count', 'asc');
}])->take(10)->get();
          return  $products;
    }
    public function getModernProducts($model,$request){
        if(auth()->guard('api')->user()==null){

        $modelData=$model->orderBy('created_at','desc')->with(['productImages'])
        ->withCount(['reviews as reviews_avg' => function($query) {
        $query->select(DB::raw('avg(rating)'));
    }])
    ->take(10)->paginate($request->total);
        
        }else{
                    $modelData=$model->orderBy('created_at', 'desc')->with(['productImages','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])
        ->withCount(['reviews as reviews_avg' => function($query) {
        $query->select(DB::raw('avg(rating)'));
    }])
    ->take(10)->paginate($request->total);
        }
       return  $modelData;
   
    }
    public function getOffersProducts($model,$request){
        
if(auth()->guard('api')->user()==null){
    $modelData=$model->where('is_offers',1)->with('category.mainCategory')->with(['productImages','reviews'])->take(10)->paginate($request->total);
}else{
    
                $modelData=$model->where('is_offers',1)->with('category.mainCategory')->with(['productImages','reviews','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])->take(10)->paginate($request->total);
}

           return  $modelData;
       
        }
        
         public function getProductsForSubCategoryTable($model,$subCategoryId){
            //               $category=  Category::where('id',$subCategoryId)->first();
            //               if($category->parnet_id==null){
            //               dd($category->parnet_id);
            //       return 400; //this category_id is main category , so cannt get this sub categrories from main category , only , can get it from sub category
            //   }
                    if(auth()->guard('api')->user()==null){

        $modelData=$model->where('sub_category_id',$subCategoryId)->with(['subCategory','productImages'])->get();
                    }else{
        $modelData=$model->where('sub_category_id',$subCategoryId)->with(['subCategory','productImages','favorites'=> function ($hasMany) {
        $hasMany->where('user_id', auth()->guard('api')->user()->id);
    }])->get();
                        
                    }
          return  $modelData;
    } 

    // methods overrides

    public function showAttributeIdForArray(Request $request){
        dd($request->attributes);
       $showAttributeIdForArray= ProductArrayAttribute::where(['attributes'=>$request->attributes])->first();
    //   dd($showAttributeIdForArray);
       if(empty($showAttributeIdForArray)){
         //  return __('not found in system , pls select again ');
           return 'غير موجود بالنظام , من فضلك حاول الاختيار مرة اخرى';
       }
        return $showAttributeIdForArray;
    }

    public function productsInInventory($model){
       $productsInInventory= $model->with('productAttributes')->get();
       return $productsInInventory;
    }

    public function deleteImage($idImage){
        
       $image= ProductImage::find($idImage);
       $image->delete();
       return 200;
        
    }

    
}
