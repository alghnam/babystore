<?php

namespace Modules\View\Http\Controllers\API\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\View\Entities\View;
use Modules\View\Http\Requests\StoreViewRequest;
use Modules\View\Http\Requests\UpdateViewRequest;
use Modules\View\Http\Requests\DeleteViewRequest;
use Modules\View\Repositories\Admin\ViewRepository;

class ViewController extends Controller
{    
        /**
    * @var BaseRepository
    */
    protected $baseRepo;
    /**
    * @var ViewRepository
    */
    protected $viewRepo;
    /**
    * @var View
    */
    protected $view;


    /**
    * ViewsController constructor.
    *
    * @param ViewRepository $views
    */
    public function __construct(BaseRepository $baseRepo, View $view,ViewRepository $viewRepo)
    {
    $this->middleware(['permission:views_read'])->only(['index','getAllPaginates']);
    $this->middleware(['permission:views_trash'])->only('trash');
    $this->middleware(['permission:views_restore'])->only('restore');
    $this->middleware(['permission:views_restore-all'])->only('restore-all');
    $this->middleware(['permission:views_show'])->only('show');
    $this->middleware(['permission:views_store'])->only('store');
    $this->middleware(['permission:views_update'])->only('update');
    $this->middleware(['permission:views_destroy'])->only('destroy');
    $this->middleware(['permission:views_destroy-force'])->only('destroy-force');
    $this->baseRepo = $baseRepo;
    $this->view = $view;
    $this->viewRepo = $viewRepo;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
    $views=$this->viewRepo->all($this->view);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Views has been getten successfully',
        'data'=> $views
    ]);
    }

    public function getAllPaginates(Request $request){
    
    $views=$this->viewRepo->getAllPaginates($this->view,$request);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Views has been getten successfully(pagination)',
        'data'=> $views
    ]);
    }




    // methods for trash
    public function trash(Request $request){
    $views=$this->viewRepo->trash($this->view,$request);

    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'Views has been getten successfully (in trash)',
        'data'=> $views
    ]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreViewRequest $request)
    {
    $view=$this->viewRepo->store($request,$this->view);
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'View has been stored successfully',
        'data'=> $view
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
    $view=$this->viewRepo->find($id,$this->view);
    
        if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
   
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'View has been getten successfully',
            'data'=> $view
        ]);
    
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateViewRequest $request,$id)
    {
    $view= $this->viewRepo->update($request,$id,$this->view);
    if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'View has been updated successfully',
        'data'=> $view
    ]);
    

    }

    public function inventory(){
    $viewsInInventory= $this->viewRepo->viewsInInventory($this->view);
    if(empty($viewsInInventory)){
    if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'ViewsInInventory getting successfully',
        'data'=> $viewsInInventory
    ]);
     
    }
    }

    //methods for restoring
    public function restore($id){
    
    $view =  $this->viewRepo->restore($id,$this->view);
     if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
    
            return response()->json([
                'status'=>true,
                'code' => 200,
                'message' => 'View has been restored',
                'data'=> $view
            ]);
        

    }
    public function restoreAll(){
    $views =  $this->viewRepo->restoreAll($this->view);
     if(is_string($views)){
            return response()->json(['status'=>false,'message'=>$views],404);
        }
        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'restored successfully',
            'data'=> $views
        ]);
    

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(DeleteViewRequest $request,$id)
    {
    $view= $this->viewRepo->destroy($id,$this->view);
     if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }
  
    return response()->json([
        'status'=>true,
        'code' => 200,
        'message' => 'destroyed  successfully',
        'data'=> $view
    ]); 
    
    }
    public function forceDelete(DeleteViewRequest $request,$id)
    {
    //to make force destroy for a View must be this View  not found in Views table  , must be found in trash Views
    $view=$this->viewRepo->forceDelete($id,$this->view);
     if(is_string($view)){
            return response()->json(['status'=>false,'message'=>$view],404);
        }

        return response()->json([
            'status'=>true,
            'code' => 200,
            'message' => 'destroyed forcely successfully ',
            'data'=> null
        ]); 
    
    }
    
    
   
}
