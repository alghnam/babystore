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

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    public function all($model){
    $modelData=$model->get();
    
       return  $modelData;
   }
public function getAllPaginates($model,$request){
    $modelData=$model->with(['user','productArrayAttributes','address','reviewOrder','couponcode','payment','service'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);

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
    
    public function getLatestOrders($model){
       $getLatestOrders= $model->latest()->take(6)->get();
       return  $getLatestOrders;
    }

    }
