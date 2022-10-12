<?php
namespace Modules\Coupon\Repositories\Admin;

use App\GeneralClasses\MediaClass;
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
use Modules\Coupon\Repositories\Admin\CouponRepositoryInterface;
use Modules\Order\Entities\Order;
use App\Scopes\ActiveScope;

class CouponRepository extends EloquentRepository implements CouponRepositoryInterface
{

    public function getAllPaginates($model,$request){
    $modelData=$model->with(['order'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
   public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
            return 'لا يوجد اي عناصر في سلة المحذوفات الى الان';
        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['order'])->paginate($request->total);
        return $modelData;
    }
        public function store($request,$model){
            $data=$request->validated();
            $data['locale']=config('app.locale');
            $data['is_used']=0;
            $coupon= $model->create($data);
            return $coupon;
    }
        public function update($request,$id,$model){

            $coupon=$this->find($id,$model);

            $data=$request->validated();
            $data['locale']=config('app.locale');
            $data['is_used']=0;
            $coupon->update($data);
            return $coupon;

    }

}
