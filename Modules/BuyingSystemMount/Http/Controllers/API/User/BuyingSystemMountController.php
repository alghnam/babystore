<?php

namespace Modules\BuyingSystemMount\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BuyingSystemMount\Entities\BuyingSystemMount;

class BuyingSystemMountController extends Controller
{
      public function getBuyingSystemMount()
        {
            $BuyingSystemMount=BuyingSystemMount::first();
            return response()->json(['status'=>true,'message'=>'data has been getten successfully','data'=>$BuyingSystemMount],200);

               
          
    }
}
