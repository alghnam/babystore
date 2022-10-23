<?php
namespace Modules\View\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\View\Repositories\User\ViewRepositoryInterface;
use Carbon\Carbon;
use Modules\Product\Entities\Product;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Repositories\BaseRepository;
class ViewRepository extends EloquentRepository implements ViewRepositoryInterface
{
            /**
     * @var BaseRepository
     */
    protected $baseRepo;
        public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }

    public function myViews($model){
              $user=auth()->guard('api')->user();
           $views= $model->where(['user_id'=>$user->id])->with(['product','product.productImages'])->paginate(10);
           if(count($views)==0){
                return 'غير موجود';
           }
            $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($views as $view){
                    //convert this price that in dinar into currency user
                    $view->currency_country=$this->baseRepo->countryCurrency();

                    $convertingOriginalPriceAttr =  $this->baseRepo->priceCalculation($view->product->original_price);
                    $view->product->original_price=$convertingOriginalPriceAttr;
                    
                    $convertingPriceEndsAttr =  $this->baseRepo->priceCalculation($view->product->price_discount_ends);
                    $view->product->price_discount_ends=$convertingPriceEndsAttr;
                
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
