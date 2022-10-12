<?php

namespace Modules\Banner\Http\Controllers\API\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\BaseRepository;

use Modules\Banner\Repositories\User\BannerRepository;
use Modules\Banner\Entities\Banner;
class BannerController extends Controller
{
     /**
     * @var BannerRepository
     */
    protected $bannerRepo;
        /**
     * @var Banner
     */
    protected $banner;
   

    /**
     * BannersController constructor.
     *
     * @param BannerRepository $favorites
     */
    public function __construct(BaseRepository $baseRepo, Banner $banner,BannerRepository $bannerRepo)
    {

        $this->baseRepo = $baseRepo;
        $this->banner = $banner;
        $this->bannerRepo = $bannerRepo;
    }
 public function getAllBannersForUserPaginate(Request $request){
     try{
        $banners=$this->bannerRepo->getAllBannersForUserPaginate($this->banner,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banners],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    } 
}
