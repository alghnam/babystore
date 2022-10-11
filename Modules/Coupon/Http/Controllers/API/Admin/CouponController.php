<?php

namespace Modules\Coupon\Http\Controllers\API\Admin;

use Modules\Coupon\Entities\Coupon;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Http\Requests\StoreCouponRequest;
use Modules\Coupon\Http\Requests\UpdateCouponRequest;
use Modules\Coupon\Http\Requests\DeleteCouponRequest;
use Modules\Coupon\Repositories\Admin\CouponRepository;

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
    $this->middleware(['permission:coupons_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:coupons_trash'])->only('trash');
    $this->middleware(['permission:coupons_restore'])->only('restore');
    $this->middleware(['permission:coupons_restore-all'])->only('restore-all');
    $this->middleware(['permission:coupons_show'])->only('show');
    $this->middleware(['permission:coupons_store'])->only('store');
    $this->middleware(['permission:coupons_update'])->only('update');
    $this->middleware(['permission:coupons_destroy'])->only('destroy');
    $this->middleware(['permission:coupons_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->coupon = $coupon;
    $this->couponRepo = $couponRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
    $coupons=$this->couponRepo->all($this->coupon);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Coupons has been getten successfully',
        'data'=> $coupons
    ]);
    }
    public function getAllPaginates(Request $request){
    
    $coupons=$this->couponRepo->getAllPaginates($this->coupon,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Coupons has been getten successfully(pagination)',
        'data'=> $coupons
    ]);
    }

    public function getCouponsForCategory($categoryId){
    $getCouponsForCategory=$this->couponRepo->getCouponsForCategory($this->coupon,$categoryId);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'CouponsForCategory  has been getten successfully',
        'data'=> $getCouponsForCategory
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $coupons=$this->couponRepo->trash($this->coupon,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Coupons has been getten successfully (in trash)',
        'data'=> $coupons
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreCouponRequest $request)
    {
    $coupon=$this->couponRepo->store($request,$this->coupon);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Coupon has been stored successfully',
        'data'=> $coupon
    ]);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    $coupon=$this->couponRepo->find($id,$this->coupon);
    if(empty($coupon)){
        return response()->json([
            'status'=>false,
            'code' => 404,
            'message' => 'there is not exit this Coupon',
            'data'=> null
        ]);
    }else{
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'Coupon has been getten successfully',
            'data'=> $coupon
        ]);
    }
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateCouponRequest $request,$id)
    {
    $coupon= $this->couponRepo->update($request,$id,$this->coupon);
              if(is_string($coupon)){
            return response()->json(['status'=>false,'message'=>$coupon],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Coupon has been updated successfully',
        'data'=> $coupon
    ]);
    

    }

    public function inventory(){
    $couponsInInventory= $this->couponRepo->couponsInInventory($this->coupon);
                  if(is_string($couponsInInventory)){
            return response()->json(['status'=>false,'message'=>$couponsInInventory],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'CouponsInInventory getting successfully',
        'data'=> $couponsInInventory
    ]);
    
    }

    //methods for restoring
    public function restore($id){
    
    $coupon =  $this->couponRepo->restore($id,$this->coupon);
                  if(is_string($coupon)){
            return response()->json(['status'=>false,'message'=>$coupon],404);
        }
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'Coupon has been restored',
                'data'=> $coupon
            ]);
        

    }
    public function restoreAll(){
    $coupons =  $this->couponRepo->restoreAll($this->coupon);
                  if(is_string($coupons)){
            return response()->json(['status'=>false,'message'=>$coupons],404);
        }
        
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $coupons
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteCouponRequest $request,$id)
    {
    $coupon= $this->couponRepo->destroy($id,$this->coupon);
                  if(is_string($coupon)){
            return response()->json(['status'=>false,'message'=>$coupon],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $coupon
    ]); 
    
    }
    public function forceDelete(DeleteCouponRequest $request,$id)
    {
    //to make force destroy for a Coupon must be this Coupon  not found in Coupons table  , must be found in trash Coupons
    $coupon=$this->couponRepo->forceDelete($id,$this->coupon);
                  if(is_string($coupon)){
            return response()->json(['status'=>false,'message'=>$coupon],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> $coupon
        ]); 

    }

}
