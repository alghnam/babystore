<?php
namespace Modules\View\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\View\Repositories\User\ViewRepositoryInterface;
use Carbon\Carbon;
use Modules\Product\Entities\Product;
class ViewRepository extends EloquentRepository implements ViewRepositoryInterface
{

    public function myViews($model){
              $user=auth()->guard('api')->user();
           $views= $model->where(['user_id'=>$user->id])->with(['product','product.productImages'])->paginate(10);
           if(count($views)==0){
            //   return __('not found');
                return 'غير موجود';
           }
           return $views;
        
    }
    
    public function addToView($model,$request){
        $data=$request->validated();
        $user=auth()->user();
       $product= Product::where(['id'=>$data['product_id']])->first();
       if($product==null){
            // return __('this product not exist in product');
            return 'هذا المنتج غير موجود بالنظام';
       }
        //get date that view this user for this product-> now()
        $viewSame=$model->where(['user_id'=>$user->id,'product_id'=>$data['product_id']])->first();
        if(!empty($viewSame)){
            // return __('this product already added into  views user');
            return 'هذا المنتج بالطبع تم اضافته الى قائمة مشاهدتك';
        }
        
       $view= $model->create(['user_id'=>$user->id,'product_id'=>$data['product_id'],'view_at'=>Carbon::now()]);
       return $view;
    }


    
    
}
