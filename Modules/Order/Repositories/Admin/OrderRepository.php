<?php
namespace Modules\Order\Repositories\Admin;

use App\GeneralClasses\MediaClass;
use App\Models\Image as ModelsImage;
use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\User\OrderRepositoryInterface;
use Modules\Order\Entities\Order;
use DB;
use Illuminate\Http\Request;
use Modules\Auth\Entities\User;
use Modules\Cart\Entities\Cart;
use Modules\Service\Entities\Service;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\AddressCodeNum;
use Modules\Order\Entities\Address;
use App\Notifications\OrderPendingNotification;
use App\Scopes\ActiveScope;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    public function all($model){
    $modelData=$model->get();
       return  $modelData;
   }
public function getAllPaginates($model,$request){
    // dd(Order::get());
    $modelData=$model->with(['user','productArrayAttributes','address','reviewOrder','couponcode','payment','service'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
         if(is_string($modelData)){
            return response()->json(['status'=>false,'message'=>$modelData],404);
        }
    // $modelData=$model->paginate($request->total);
       return  $modelData;
    }
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
                            return trans('messages.there is not found any items in trash');

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['user','productArrayAttributes','address','reviewOrder','couponcode','payment','service'])->paginate($request->total);
        return $modelData;
    }

    //       public  function trash($model,$request){

    //   $modelData=$this->findAllItemsOnlyTrashed($model);
    //             if(is_string($modelData)){
    //         return response()->json(['status'=>false,'message'=>$modelData],404);
    //     }
    //   $modelData=$this->findAllItemsOnlyTrashed($model)->with(['user','productArrayAttributes','addresses','reviewOrder','couponcode','payment','service'])->paginate($request->total);
        
    //     return $modelData;
    // }
    
        //هنا  مجرد بنخليه يدخخل الكارت ايدي عشان نعرف كم عدد المنتجات اللي بالكارت وتلقائي عددهم بييجي بالبردوكتز كاونت فمتلا بدي 
        //اعمل ادارة لاوردر معين وبدي اغير هادا الاوردر بدي اخليه تابع  لكارت تانية لا ما بزبط العب ب هلشي الادارة للوردر حتكون بتحديد 
        //السيرفيس والبيمنت والعنوان وكوبون الخصم مش مني بردو حسب م اليوزر حط الكوبون اللي بدو اياه 
        //والسعر بردو ا العب فيه لانو بردو من المنتجات تاعة سلة اليوزر اللي حكينا ما بزبط يلعب فيها 
        //بس السيرفيس والبيمنت والعنوان ورقم الاوردر والستيتس وبس هدول بس بالستور والابديت والقراءة عادي بقدر يشوف كلشي 
//كوبون الخصم مش من هنا بادارة الكوبونوز كل كوبون قدامو الاوردر اللي استخدم فيه 
//وبردو الريفيوز ما بزبط الادمن اللي يعدل فيهم لانو هم من اليوزرز انكتبو الافضل ما يتعدل فيه 

//كل هالكلام عشان ما يصير لخبطة من السيستم فكل شي الو اصل متلا الاوردر اصلو العميل هو اللي يعملو وهادا الادمن يعمل ادارة عليه اي قراءة ويعدل الاشياء اللي بتنفع تعديلها بدون اللخبطة 
//ما بنفع يعدل بالاوردر باي شي بس بالستيتس تاعه وبس 
}
