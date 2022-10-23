<?php
namespace Modules\Order\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\User\OrderRepositoryInterface;
use Modules\Order\Entities\Order;
use DB;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Modules\Cart\Entities\Cart;
use Modules\Service\Entities\Service;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Order\Entities\Address;
use App\Notifications\OrderPendingNotification;
use App\Scopes\ActiveScope;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Repositories\BaseRepository;
class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
        public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }

    public function all($model){
    $modelData=$model->get();
    
       return  $modelData;
   }
    public function countsAllData(){
      $orders = Order::count();
      $products = Product::count();
      $users = User::count();
        $finishedOrders=Order::where('status',3)->count();
        $countsAllData=[];
        array_push($countsAllData,['title'=>'Orders','total'=>$orders]);
        array_push($countsAllData,['title'=>'Users','total'=>$users]);
        array_push($countsAllData,['title'=>'Products','total'=>$products]);
        array_push($countsAllData,['title'=>'Sent Delivered Orders','total'=>$finishedOrders]);
        return $countsAllData;
   }
    public function getAllPaginates($model,$request){
        $modelData=$model->with(['user','productArrayAttributes','address','reviewOrder','couponcode','payment','service'])->latest()->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
    
           return  $modelData;
        }
        
       public  function trash($model,$request){
           $modelData=$this->findAllItemsOnlyTrashed($model);
            if(is_string($modelData)){
                return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
    
            }
           $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['user','productArrayAttributes','address','reviewOrder','couponcode','payment','service'])->paginate($request->total);
            return $modelData;
    }
    public function sentDeliveredOrders($model){
        $modelData=$model->where('status',3)->count();
        return $modelData;
    }
     public function shippingOrders($model){
        $modelData=$model->where('status',2)->count();
        return $modelData;
    }
    public function pricesSentDeliveredOrders($model){
        $modelData=$model->where('status',3)->get();
        $totalPrices=0;
        foreach($modelData as $order){
            $totalPrices=$totalPrices+$order->price;    
        }
        $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                $totalPrices =  $this->baseRepo->priceCalculation($totalPrices);
            }
        return $totalPrices;
    }
    public function getLatestOrders($model){
       $getLatestOrders= $model->with(['user','payment','service','coupon','address'])->latest()->take(10)->get();
       return  $getLatestOrders;
    }
    
    public function getOrdersGroupMonth($model){
        $months=[1,2,3,4,5,6,7,8,9,10,11,12];
       // $monthsNames=["Jan","Feb","March","April","May","June","July","Aug","Sep","Oct","Nov","Dec"];
         $arrOrdersGroupMonth=[];
         $total=0;
        $location = geoip(request()->ip());
        foreach($months as $month){
         $totalPrices=0;
            $getOrdersGroupMonth= $model->whereMonth('created_at', '=', $month)->get();
            foreach($getOrdersGroupMonth as $order){
                $totalPrices=$totalPrices+$order->price;
                if($location->currency!==config('constants.currency_system')){
                    $totalPrices =  $this->baseRepo->priceCalculation($totalPrices);
                }
            }
        //    array_push($arrOrdersGroupMonth,['month'=>$month,'prices'=>$totalPrices]);
            array_push($arrOrdersGroupMonth,$totalPrices);
        }

       return  $arrOrdersGroupMonth;
    }

    }
