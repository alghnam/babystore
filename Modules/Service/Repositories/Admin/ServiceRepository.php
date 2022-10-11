<?php
namespace Modules\Service\Repositories\Admin;

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
use App\Scopes\ActiveScope;

class ServiceRepository extends EloquentRepository implements ServiceRepositoryInterface
{
        public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
  

}
