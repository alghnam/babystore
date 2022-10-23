<?php
namespace Modules\Banner\Repositories\User;

use App\Repositories\EloquentRepository;

use Modules\Banner\Repositories\User\BannerRepositoryInterface;
class BannerRepository extends EloquentRepository implements BannerRepositoryInterface
{

    public function getAllBannersForUserPaginate($model,$request){
    $modelData=$model->with(['image'])->paginate($request->total);
       return  $modelData;
   
    }    

    
}
