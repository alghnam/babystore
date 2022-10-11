<?php
namespace Modules\Movement\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Movement\Repositories\Admin\MovementRepositoryInterface;
use App\Scopes\ActiveScope;

class MovementRepository extends EloquentRepository implements MovementRepositoryInterface
{
        public function getAllPaginates($model,$request){
        $modelData=$model->with('wallet')->withoutGlobalScope(ActiveScope::class)->latest()->paginate($request->total);
          return  $modelData;
    }
  

}
