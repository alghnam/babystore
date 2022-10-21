<?php

namespace Modules\Banner\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Banner\Http\Requests\DeleteBannerRequest;
use Modules\Banner\Http\Requests\StoreBannerRequest;
use Modules\Banner\Http\Requests\UpdateBannerRequest;
use Modules\Banner\Repositories\Admin\BannerRepository;
use  Modules\Banner\Entities\Banner;
use Modules\Banner\Http\Requests\AddToBannerRequest;

class BannerController extends Controller
{
          /**
     * @var BaseRepository
     */
    protected $baseRepo;
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
     * @param BannerRepository $banners
     */
    public function __construct(BaseRepository $baseRepo, Banner $banner,BannerRepository $bannerRepo)
    {
        $this->middleware(['permission:banners_read'])->only(['index','getAllPaginates']);
        $this->middleware(['permission:banners_trash'])->only('trash');
        $this->middleware(['permission:banners_restore'])->only('restore');
        $this->middleware(['permission:banners_restore-all'])->only('restore-all');
        $this->middleware(['permission:banners_show'])->only('show');
        $this->middleware(['permission:banners_store'])->only('store');
        $this->middleware(['permission:banners_update'])->only('update');
        $this->middleware(['permission:banners_destroy'])->only('destroy');
        $this->middleware(['permission:banners_destroy-force'])->only('destroy-force');
        $this->baseRepo = $baseRepo;
        $this->banner = $banner;
        $this->bannerRepo = $bannerRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
          try{
        $banners=$this->bannerRepo->all($this->banner);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banners],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getAllPaginates(Request $request){
        
         try{
        $banners=$this->bannerRepo->getAllPaginates($this->banner,$request);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banners],200);

        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function getBannersProduct(Request $request,$productId){
        
         try{
        $banners=$this->bannerRepo->getBannersProduct($this->banner,$request,$productId);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banners],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }




    // methods for trash
    public function trash(Request $request){
  try{
        $banners=$this->bannerRepo->trash($this->banner,$request);
        if(is_string($banners)){
            return response()->json(['status'=>false,'message'=>$banners],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banners],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBannerRequest $request)
    {
         try{
       $banner= $this->bannerRepo->store($request,$this->banner);
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner->load(['image','product'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
              try{
        $banner=$this->bannerRepo->find($id,$this->banner);
            if(is_string($banner)){
            return response()->json(['status'=>false,'message'=>$banner],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBannerRequest $request,$id)
    {

          try{
       $banner= $this->bannerRepo->update($request,$id,$this->banner);
                    if(is_string($banner)){
            return response()->json(['status'=>false,'message'=>$banner],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner->load(['image','product'])],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }

    //methods for restoring
    public function restore($id){
        
          try{
        $banner =  $this->bannerRepo->restore($id,$this->banner);
                    if(is_string($banner)){
            return response()->json(['status'=>false,'message'=>$banner],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 

    }
    public function restoreAll(){
          try{
        $banners =  $this->bannerRepo->restoreAll($this->banner);
                    if(is_string($banners)){
            return response()->json(['status'=>false,'message'=>$banners],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banners],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        }
        
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBannerRequest $request,$id)
    {
          try{
       $banner= $this->bannerRepo->destroy($id,$this->banner);
            if(is_string($banner)){
            return response()->json(['status'=>false,'message'=>$banner],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
       
    }
    public function forceDelete(DeleteBannerRequest $request,$id)
    {
          try{
        //to make force destroy for a Banner must be this Banner  not found in Banners table  , must be found in trash Banners
        $banner=$this->bannerRepo->forceDelete($id,$this->banner);
            if(is_string($banner)){
            return response()->json(['status'=>false,'message'=>$banner],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
    }
        public function deleteImage($id){
          try{
        $banner= $this->bannerRepo->deleteImage($id,$this->product);
         if(is_string($banner)){
            return response()->json(['status'=>false,'message'=>$banner],404);
        }
          return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$banner],200);

        
        }catch(\Exception $ex){
            return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        } 
        
        
    }



 
}
