<?php

namespace Modules\Cart\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cart\Entities\Cart;

use Modules\Cart\Http\Requests\StoreCartRequest;
use Modules\Cart\Http\Requests\UpdateCartRequest;
use Modules\Cart\Http\Requests\DeleteCartRequest;
use Modules\Cart\Repositories\Admin\CartRepository;

class CartController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var CartRepository
    */
    protected $cartRepo;
    /**
    * @var Cart
    */
    protected $cart;


    /**
    * CartsController constructor.
    *
    * @param CartRepository $carts
    */
    public function __construct(BaseRepository $baseRepo, Cart $cart,CartRepository $cartRepo)
    {
    $this->middleware(['permission:carts_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:carts_trash'])->only('trash');
    $this->middleware(['permission:carts_restore'])->only('restore');
    $this->middleware(['permission:carts_restore-all'])->only('restore-all');
    $this->middleware(['permission:carts_show'])->only('show');
    $this->middleware(['permission:carts_store'])->only('store');
    $this->middleware(['permission:carts_update'])->only('update');
    $this->middleware(['permission:carts_destroy'])->only('destroy');
    $this->middleware(['permission:carts_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->cart = $cart;
    $this->cartRepo = $cartRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        try{
            $carts=$this->cartRepo->all($this->cart);
             return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$carts],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
   
    }

    public function getAllPaginates(Request $request){
        try{
            $carts=$this->cartRepo->getAllPaginates($this->cart,$request);
             return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$carts],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    public function getAllProductArrayAttributesCartPaginates(Request $request,$id){
        try{
            $carts=$this->cartRepo->getAllProductArrayAttributesCartPaginates($this->cart,$request,$id);
            if(is_string($carts)){
                return response()->json(['status'=>false,'message'=>$carts],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$carts],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    // methods for trash
    public function trash(Request $request){
        try{
            $carts=$this->cartRepo->trash($this->cart,$request);
            if(is_string($carts)){
                return response()->json(['status'=>false,'message'=>$carts],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$carts],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreCartRequest $request)
    {
        try{
            $cart=$this->cartRepo->store($request,$this->cart);
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        try{
            $cart=$this->cartRepo->find($id,$this->cart);
    
            if(is_string($cart)){
                return response()->json(['status'=>false,'message'=>$cart],404);
            }
   
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateCartRequest $request,$id)
    {
        try{
            $cart= $this->cartRepo->update($request,$id,$this->cart);
            if(is_string($cart)){
                    return response()->json(['status'=>false,'message'=>$cart],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    

    }

 
    //methods for restoring
    public function restore($id){
        try{
            $cart =  $this->cartRepo->restore($id,$this->cart);
            if(is_string($cart)){
                    return response()->json(['status'=>false,'message'=>$cart],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
        

    }
    public function restoreAll(){
        try{
            $carts =  $this->cartRepo->restoreAll($this->cart);
            
             if(is_string($carts)){
                    return response()->json(['status'=>false,'message'=>$carts],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$carts],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
       
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteCartRequest $request,$id)
    {
        try{
            $cart= $this->cartRepo->destroy($id,$this->cart);
             if(is_string($cart)){
                    return response()->json(['status'=>false,'message'=>$cart],404);
                }
  
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    
    }
    public function forceDelete(DeleteCartRequest $request,$id)
    {
        try{
            //to make force destroy for a Cart must be this Cart  not found in Carts table  , must be found in trash Carts
            $cart=$this->cartRepo->forceDelete($id,$this->cart);
             if(is_string($cart)){
                    return response()->json(['status'=>false,'message'=>$cart],404);
                }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$cart],200);
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    
    
    }
    
    
   
}
