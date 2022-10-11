<?php
namespace Modules\Service\Repositories\User;

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
use Modules\Order\Repositories\OrderRepositoryInterface;
use Modules\Order\Entities\Order;
use DB;
use Illuminate\Http\Request;
use Modules\Cart\Entities\Cart;
use Modules\Coupon\Entities\Coupon;
use Modules\Service\Entities\Service;

class ServiceRepository extends EloquentRepository implements ServiceRepositoryInterface
{

      public function getServices(){
        $services = Service::get();
        return $services;
      }

      public function showService($model,$id){
        $service = $model->where('id',$id)->first();
        if(empty($service)){
            // return __('not found in system');
            return 'غير موجود بالنظام';
        }
        return $service;
      }
  
}
