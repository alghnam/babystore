<?php
namespace Modules\Category\Repositories\User;

use App\Repositories\EloquentRepository;
use Image;
use Illuminate\Support\Arr;
use Modules\Category\Entities\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Repositories\User\CategoryRepositoryInterface;
use App\Repositories\BaseRepository;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{
     public function __construct(BaseRepository $baseRepo)
     {

         $this->baseRepo = $baseRepo;
     }

    public function getMainCategoriesPaginate($model,$request){
          $mainCategories=$model->where(['parent_id'=>null])->paginate($request->total);
        return $mainCategories;
    }   

        public function getSubCategoriesForMainCategoryPaginate($model,$request){
        $modelData=$model->where('parent_id','!=',null)->with(['image','products','products.productImages'])->paginate($request->total);
                             $location = geoip(request()->ip());
            if($location->currency!==config('constants.currency_system')){
                foreach($modelData as $pro){
                    //convert this price that in dinar into currency user
                    $pro->currency_country=$this->baseRepo->countryCurrency();
    
                    if($pro->original_price){
                        $convertingOriginalPrice =  $this->baseRepo->priceCalculation($pro->original_price);
                        $pro->original_price=$convertingOriginalPrice;
                    }
                    if($pro->price_discount_ends){
                        $convertingPriceEnds =  $this->baseRepo->priceCalculation($pro->price_discount_ends);
                        $pro->price_discount_ends=$convertingPriceEnds;
                    }
                }
            }
          return  $modelData;
    }     
    public function getSubCategoriesForSubCategoryPaginate($model,$request,$categoryId){
      $category=  Category::where('id',$categoryId)->first();
      if($category->parnet_id=="null"){
          return 400; //this category_id is main category , so cannt get this sub categrories from main category , only , can get it from sub category
      }
        $modelData=$model->where('category_id',$categoryId)->with(['image','subCategory','products','products.productImages'])->paginate($request->total);
        return  $modelData;
    }     
    
    public function getSubCategories($model,$request){
        $modelData=$model->where('parent_id','!=',null)->paginate($request->total);
          return  $modelData;
    }  
    
    public function getSecondSubCategoriesPaginate($model,$request){
                $modelData=$model->with(['image'])->paginate($request->total);
          return  $modelData;
    }
}
