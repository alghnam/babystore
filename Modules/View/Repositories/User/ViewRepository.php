<?php
namespace Modules\View\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\View\Repositories\User\ViewRepositoryInterface;
use Carbon\Carbon;
use Modules\Product\Entities\Product;
use AmrShawky\LaravelCurrency\Facade\Currency;

class ViewRepository extends EloquentRepository implements ViewRepositoryInterface
{

    public function myViews($model){
              $user=auth()->guard('api')->user();
           $views= $model->where(['user_id'=>$user->id])->with(['product','product.productImages'])->paginate(10);
           if(count($views)==0){
                return 'غير موجود';
           }
                       $location = geoip(request()->ip());
            $currencyCountry=$location->currency;
                $currencySystem='KWD';
            if($location->currency!==$currencySystem){
                foreach($views as $view){
                //convert this price that in dinar into currency user
                   
                $view->currency_country=$location->currency;
                    $convertingOriginalPriceAttr=  Currency::convert()
                        ->from($currencySystem)
                        ->to($currencyCountry)
                        ->amount($view->product->original_price)
                        ->get();
                
                    $view->product->original_price=round($convertingOriginalPriceAttr,2);
                    
                    $convertingPriceEndsAttr=  Currency::convert()
                        ->from($currencySystem)
                        ->to($currencyCountry)
                        ->amount($view->product->price_discount_ends)
                        ->get();
                
                    $view->product->price_discount_ends=round($convertingPriceEndsAttr,2);
                
            }
            }
         
           return $views;
        
    }
    
    public function addToView($model,$request){
        $data=$request->validated();
        $user=auth()->user();
       $product= Product::where(['id'=>$data['product_id']])->first();
       if($product==null){
            return 'هذا المنتج غير موجود بالنظام';
       }
        //get date that view this user for this product-> now()
        $viewSame=$model->where(['user_id'=>$user->id,'product_id'=>$data['product_id']])->first();
        if(!empty($viewSame)){
            return 'هذا المنتج بالطبع تم اضافته الى قائمة مشاهدتك';
        }
        
       $view= $model->create(['user_id'=>$user->id,'product_id'=>$data['product_id'],'view_at'=>Carbon::now()]);
       return $view;
    }


    
    
}
