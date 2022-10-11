<?php
namespace Modules\Rule\Repositories\Admin;

use App\Repositories\EloquentRepository;

use Modules\Rule\Repositories\Admin\RuleRepositoryInterface;
use App\Scopes\ActiveScope;

class RuleRepository extends EloquentRepository implements RuleRepositoryInterface
{

    public function getAllPaginates($model,$request){
        $modelData=$model->withoutGlobalScope(ActiveScope::class)->paginate($request->total);
          return  $modelData;
    }
}
