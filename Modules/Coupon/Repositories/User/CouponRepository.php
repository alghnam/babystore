<?php
namespace Modules\Coupon\Repositories\User;

use App\Models\Coupon;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Auth\Entities\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Coupon\Repositories\User\CouponRepositoryInterface;
use Modules\Order\Entities\Order;
use App\Models\TempDataUser;
use AmrShawky\LaravelCurrency\Facade\Currency;

class CouponRepository extends EloquentRepository implements CouponRepositoryInterface
{

    public function getAllCouponsPaginate($model,$request){
    $modelData=$model->with(['product'])->paginate($request->total);
       return  $modelData;
   
    }

           public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['product'])->paginate($request->total);
        return $modelData;
    }

    public function getCoupons($status,$model,$request){
            $getCoupons = $model->where(['is_used'=>$status])->where('end_date','>',now()->addHour())->take(8)->paginate($request->total);//get coupon  not used and not reach into end date
        
       return $getCoupons;
    }
        public function getEndedCoupons($model,$request){
       $getCoupons = $model->where('end_date','<',now()->addHour())->take(8)->paginate($request->total);
       return $getCoupons;
    }
    public function storeCouponOrder($request,$model){
        $data=$request->validated();

        $user=auth()->guard('api')->user();
        $coupon = $model->where('name',$data['name'])->first();
        
        if(!$coupon){
            return 'هذا الكوبون غير موجود ';
        }
        if($coupon->is_used==1){
            return 'هذا الكوبون تم استخدامه من قبل';
        }
         if($coupon->end_date<now()){
            return 'لا تستطيع استخدام هذا الكوبون لانه انتهت صلاحيته';
        }
        
        //convert this price that in dinar into currency user
        $location = geoip(request()->ip());
        $currencyCountry=$location->currency;
        $coupon->currency_country=$currencyCountry;
        $currencySystem='KWD';
        if($location->currency!==$currencySystem){
        $currencyCountry=geoip(request()->ip())->currency;
        $convertingCurrenciesCoupon=  Currency::convert()
            ->from($currencySystem)
            ->to($currencyCountry)
            ->amount($coupon->value)
            ->get();
        $coupon->value=round($convertingCurrenciesCoupon,2);
        }
        Storage::put($user->id.'-coupon_name',$coupon->name);
        Storage::put($user->id.'-coupon_value',$coupon->value);

        return $coupon;
        

    }
    public function deleteCouponOrder($model,$couponId){
        $user=auth()->guard('api')->user();

        $couponExpired = $model->where(['id'=>$couponId])->where('end_date','<',now())->first();
        if(!empty($couponExpired)){
            return 'لا يمكنك استخدام هذا الكوبون لانه انتهت صلاحيته';
        }
        $coupon = $model->where(['name'=>Storage::get($user->id.'-coupon_name'),'is_used'=>1])->first();

        if($coupon){
            return 'لا يمكن حذف هذا الكوبون لانه الان يستخدم مع كوبون بالفعل ';
        }else{
          $couponNotUse = $model->where(['name'=>Storage::get($user->id.'-coupon_name')])->first();

            $couponStorage=Storage::get($user->id.'-coupon_name');
            if($couponStorage==null){
                return 'هذا الكوبون تم حذفه من قبل بالفعل';
            }
            $couponNotUse->is_used=0;
            $couponNotUse->save();
            Storage::put($user->id.'-coupon_name',null);
            Storage::put($user->id.'-coupon_value',null);
        }

        

        return 200;
    }



}
