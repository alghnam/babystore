<?php
namespace Modules\Wallet\Repositories\Admin;

use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Modules\Wallet\Entities\Wallet;
use App\Scopes\ActiveScope;

class WalletRepository extends EloquentRepository implements WalletRepositoryInterface
{
          public function getAllPaginates($model,$request){
        $modelData=$model->with(['user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
       public  function trash($model,$request){
       $modelData=$this->findAllItemsOnlyTrashed($model);
        if(is_string($modelData)){

                            return 'لا يوجد اي عنصر في سلة المحذوفات';

        }
       $modelData=$this->findAllItemsOnlyTrashed($model)->with(['user'])->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
        return $modelData;
    }
  

}
