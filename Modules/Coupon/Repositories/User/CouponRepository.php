<?php
namespace Modules\Coupon\Repositories\User;

use App\GeneralClasses\MediaClass;
use App\Models\Coupon;
use App\Models\Image as ModelsImage;
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

  //بس مجرد اليوزر عليه يكتب اسمه وحنطلعله قيمته ونحكيله تمام
  //ولو مش موجود اسمه نحكيلو مش موجود ولو كتب اسم ب هعالاسم مش لهادا الاوردر نحكيلو هادا الاسم مش لهادا الاوردر 
//المفروض هادا اليوزر اكريتله اوردر وهادا الاوردر احطه بجدول الكوبونز مجرد م يعم جيت للكارت عشان اول م ييجي هنا تشي عالكويون يلاقيه

    //for user
    // public function storeCouponOrder($request,$model){
    //     $data=$request->validated();
    //     $user=auth()->guard('api')->user();
    //     $order=Order::where('user_id',$user->id)->first();
    //     if($order){
    //         $coupon = $model->where('name',$data['name'])->first();
    //         if(!$coupon){
    //             return 400;
    //         }
    //     //    if((string)$coupon->order_id == strval($orderId)){
    //    //     if($coupon->order_id == $order->id){
    //         Storage::put('coupon_name',$coupon->name);
    //         Storage::put('coupon_value',$coupon->value);
    
    //         $coupon_value=Storage::get('coupon_value');
    //           return $coupon_value;
    //       // }else{
    //     //       return 400; // this coupon not for this order
    //      //  }
    //     }

    // }
    public function getCoupons($status,$model,$request){
    //   $getCoupons = $model->where(['status'=>$status])->where('end_date','>=',now()->addHour())->take(8)->paginate($request->total);//get coupon used or not used and not reach into end date
       $getCoupons = $model->where(['is_used'=>$status])->take(8)->paginate($request->total);//get coupon used or not used and not reach into end date
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
                // return __('not found coupon');
                return 'هذا الكوبون غير موجود ';
            }
            if($coupon->is_used==1){
                // return __('This coupon has already been used');
                return 'هذا الكوبون تم استخدامه من قبل';
            }
             if($coupon->end_date<now()){
                // return __('This coupon has already been used');
                return 'لا تستطيع استخدام هذا الكوبون لانه انتهت صلاحيته';
            }

            // Storage::put($user->id.'-coupon_name',$coupon->name);
            // Storage::put($user->id.'-coupon_value',$coupon->value);
            //               $TempDataUser= TempDataUser::where(['user_id'=>$user->id])->first();

            //      if(empty($TempDataUser)){
            //      $TempDataUser=new TempDataUser();
            //      $TempDataUser->user_id=$user->id;
            //     $TempDataUser->coupon_name=$coupon->name;
            //     $TempDataUser->coupon_value=$coupon->value;
            //       $TempDataUser->save();
            //   }else{
            //   $TempDataUser->coupon_name=$coupon->name;
            //     $TempDataUser->coupon_value=$coupon->value;
            //       $TempDataUser->save();
            //   }
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
                        // return __('cannt delete this coupon because this coupon now is connecting with an order');
                        return 'لا يمكن حذف هذا الكوبون لانه الان يستخدم مع كوبون بالفعل ';
                    }else{
                                            $couponNotUse = $model->where(['name'=>Storage::get($user->id.'-coupon_name')])->first();

                        $couponStorage=Storage::get($user->id.'-coupon_name');
                        if($couponStorage==null){
                            // return __('this coupon arleady deleted in prevouis time');
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
