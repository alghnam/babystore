<?php

namespace Modules\Coupon\Http\Controllers\API\User;

use Modules\Coupon\Entities\Coupon;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Http\Requests\AddCouponRequest;
use Modules\Coupon\Repositories\User\CouponRepository;

class CouponController extends Controller
{
    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var CouponRepository
    */
    protected $couponRepo;
    /**
    * @var Coupon
    */
    protected $coupon;


    /**
    * CouponsController constructor.
    *
    * @param CouponRepository $coupons
    */
    public function __construct(BaseRepository $baseRepo, Coupon $coupon,CouponRepository $couponRepo)
    {
    $this->middleware(['permission:coupons_get'])->only(['getEndedCoupons','getCoupons']);
    $this->middleware(['permission:coupons_add'])->only('storeCouponOrder');
    $this->middleware(['permission:coupons_remove'])->only('deleteCouponOrder');
    
    $this->baseRepo = $baseRepo;
    $this->coupon = $coupon;
    $this->couponRepo = $couponRepo;
    }

    //for user
            public function getEndedCoupons(Request $request)
    {
        try{
            $coupons=$this->couponRepo->getEndedCoupons($this->coupon,$request);

            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$coupons],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }
        public function getCoupons($status,Request $request)
    {
        try{
    $coupons=$this->couponRepo->getCoupons($status,$this->coupon,$request);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$coupons],200);
            
                
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    
}
    public function storeCouponOrder(AddCouponRequest $request)
    {
        // try{
            $coupon=$this->couponRepo->storeCouponOrder($request,$this->coupon);
            if(is_string($coupon)){
                return response()->json(['status'=>false,'message'=>$coupon],400);
            }
            $location = geoip(request()->ip());
            $currencyCountry=$location->currency;
            $coupon->currency_country=$currencyCountry;
            
          
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$coupon],200);
            
                
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    
 

    
}
    public function deleteCouponOrder($couponId)
    {
        // try{
    $coupon=$this->couponRepo->deleteCouponOrder($this->coupon,$couponId);
                if(is_string($coupon)){
            return response()->json(['status'=>false,'message'=>$coupon],400);
        }
    
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$coupon],200);
            
                
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
    
    
    
    
    }

}
