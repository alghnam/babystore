<?php
namespace Modules\Search\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Search\Repositories\RuleRepositoryInterface;
use App\Scopes\ActiveScope;

class SearchRepository extends EloquentRepository implements SearchRepositoryInterface
{
        public function getAllPaginates($model,$request){
            $modelData=$model->withoutGlobalScope(ActiveScope::class)->with('user')->paginate($request->total);
            return  $modelData;
            
    }
  

}
