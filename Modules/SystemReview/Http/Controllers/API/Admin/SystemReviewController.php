<?php

namespace Modules\SystemReview\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SystemReview\Entities\SystemReview;
use Modules\SystemReview\Http\Requests\StoreSystemReviewRequest;
use Modules\SystemReview\Http\Requests\UpdateSystemReviewRequest;
use Modules\SystemReview\Http\Requests\DeleteSystemReviewRequest;
use Modules\SystemReview\Repositories\Admin\SystemReviewRepository;

class SystemReviewController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var SystemReviewRepository
    */
    protected $systemReviewRepo;
    /**
    * @var SystemReview
    */
    protected $systemReview;


    /**
    * SystemReviewsController constructor.
    *
    * @param SystemReviewRepository $systemReviews
    */
    public function __construct(BaseRepository $baseRepo, SystemReview $systemReview,SystemReviewRepository $systemReviewRepo)
    {
    $this->middleware(['permission:system_reviews_read'])->only([['index','getAllPaginates']]);
    $this->middleware(['permission:system_reviews_trash'])->only('trash');
    $this->middleware(['permission:system_reviews_restore'])->only('restore');
    $this->middleware(['permission:system_reviews_restore-all'])->only('restore-all');
    $this->middleware(['permission:system_reviews_show'])->only('show');
    $this->middleware(['permission:system_reviews_store'])->only('store');
    $this->middleware(['permission:system_reviews_update'])->only('update');
    $this->middleware(['permission:system_reviews_destroy'])->only('destroy');
    $this->middleware(['permission:system_reviews_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->systemReview = $systemReview;
    $this->systemReviewRepo = $systemReviewRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
    $systemReviews=$this->systemReviewRepo->all($this->systemReview);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'SystemReviews has been getten successfully',
        'data'=> $systemReviews
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $systemReviews=$this->systemReviewRepo->getAllPaginates($this->systemReview,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'SystemReviews has been getten successfully(pagination)',
        'data'=> $systemReviews
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $systemReviews=$this->systemReviewRepo->trash($this->systemReview,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'SystemReviews has been getten successfully (in trash)',
        'data'=> $systemReviews
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreSystemReviewRequest $request)
    {
    $systemReview=$this->systemReviewRepo->store($request,$this->systemReview);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'SystemReview has been stored successfully',
        'data'=> $systemReview
    ]);
    }
    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    $systemReview=$this->systemReviewRepo->find($id,$this->systemReview);
    
        if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'SystemReview has been getten successfully',
            'data'=> $systemReview
        ]);
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateSystemReviewRequest $request,$id)
    {
    $systemReview= $this->systemReviewRepo->update($request,$id,$this->systemReview);
    if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'SystemReview has been updated successfully',
        'data'=> $systemReview
    ]);
    

    }

    public function inventory(){
    $systemReviewsInInventory= $this->systemReviewRepo->systemReviewsInInventory($this->systemReview);
    if(empty($systemReviewsInInventory)){
    if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'SystemReviewsInInventory getting successfully',
        'data'=> $systemReviewsInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $systemReview =  $this->systemReviewRepo->restore($id,$this->systemReview);
     if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'SystemReview has been restored',
                'data'=> $systemReview
            ]);
        

    }
    public function restoreAll(){
    $systemReviews =  $this->systemReviewRepo->restoreAll($this->systemReview);
     if(is_string($systemReviews)){
            return response()->json(['status'=>false,'message'=>$systemReviews],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $systemReviews
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteSystemReviewRequest $request,$id)
    {
    $systemReview= $this->systemReviewRepo->destroy($id,$this->systemReview);
     if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $systemReview
    ]); 
    
    }
    public function forceDelete(DeleteSystemReviewRequest $request,$id)
    {
    //to make force destroy for a SystemReview must be this SystemReview  not found in SystemReviews table  , must be found in trash SystemReviews
    $systemReview=$this->systemReviewRepo->forceDelete($id,$this->systemReview);
     if(is_string($systemReview)){
            return response()->json(['status'=>false,'message'=>$systemReview],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
