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

class ServiceRepository extends EloquentRepository implements ServiceRepositoryInterface
{

      public function getServices(){
        $services = Service::get();
        
         $userIp = request()->ip();
            $location = geoip($userIp);
    
           
    //convert this price that in dinar into currency user
        $location = geoip(request()->ip());
        $currencySystem='KWD';
        $services->currency_country=$currencyCountry;

        if($location->currency!==$currencySystem){
        foreach($services as $service){
         $convertingCurrenciesValue=  Currency::convert()
            ->from($currencySystem)
            ->to($currencyCountry)
            ->amount($service->value)
            ->get();
            $service->value=round($convertingCurrenciesValue, 2);
            
        }
        return $services;
      }
        }
      public function showService($model,$id){
        $service = $model->where('id',$id)->first();

        if(empty($service)){
            return 'غير موجود بالنظام';
        }
        return $service;
      }
  
}
