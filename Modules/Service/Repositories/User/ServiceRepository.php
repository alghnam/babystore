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
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Repositories\BaseRepository;


class ServiceRepository extends EloquentRepository implements ServiceRepositoryInterface
{
        public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }

      public function getServices(){
        $services = Service::get();
        //convert this price that in dinar into currency user
        $location = geoip(request()->ip());
       if($location->currency!==config('constants.currency_system')){
        foreach($services as $service){
            //convert this price that in dinar into currency user
            $service->currency_country=$this->baseRepo->countryCurrency();
            
            $convertingCurrenciesValue =  $this->baseRepo->priceCalculation($service->value);
            $service->value=$convertingCurrenciesValue;
            
            
        }
        return $services;
      }
        }
      public function showService($model,$id){
        $service = $model->where('id',$id)->first();

        if(empty($service)){
            return 'غير موجود بالنظام';
        }
        $location = geoip(request()->ip());
        if($location->currency!==config('constants.currency_system')){
            //convert this price that in dinar into currency user
            $service->currency_country=$this->baseRepo->countryCurrency();
            
            $convertingCurrenciesValue =  $this->baseRepo->priceCalculation($service->value);
            $service->value=$convertingCurrenciesValue;
        }
        return $service;
      }
  
}
