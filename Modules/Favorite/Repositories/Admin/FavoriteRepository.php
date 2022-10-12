<?php
namespace Modules\Favorite\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Favorite\Repositories\Admin\FavoriteRepositoryInterface;
use App\Scopes\ActiveScope;

class FavoriteRepository extends EloquentRepository implements FavoriteRepositoryInterface
{

    public function getAllFavoritesPaginate($model,$request){
    $modelData=$model->with(['product','user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }    
    public function getAllPaginates($model,$request){
    $modelData=$model->with(['product','user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
       return  $modelData;
   
    }
             public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){
                            return 'لا يوجد اي عنصر في سلة المحذوفات';

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->withoutGlobalScope(ActiveScope::class)->with(['product','user'])->paginate($request->total);
        return $modelData;
    }

    
}
