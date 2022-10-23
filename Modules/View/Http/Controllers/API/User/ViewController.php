<?php

namespace Modules\View\Http\Controllers\API\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use  Modules\View\Entities\View;

use App\Repositories\BaseRepository;
use Modules\View\Repositories\User\ViewRepository;
use Modules\View\Http\Requests\AddToViewRequest;
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
        $this->baseRepo = $baseRepo;
        $this->view = $view;
        $this->viewRepo = $viewRepo;
    }

    
    ////////////////
    public function myViews(){
        // try{
            $myViews=$this->viewRepo->myViews($this->view);
            if(is_string($myViews)){
                return response()->json(['status'=>false,'message'=>$myViews],404);
            }
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$myViews],200);
        // }catch(\Exception $ex){
        //     return response()->json(['status'=>false,'message'=>config('constants.error')],500);

        // } 
}

    public function AddToView(AddToViewRequest $request){
        try{
            $view=$this->viewRepo->AddToView($this->view,$request);
            if(is_string($view)){
                return response()->json(['status'=>false,'message'=>$view],400);
            }
    
            return response()->json(['status'=>true,'message'=>config('constants.success'),'data'=>$view],200);
        }catch(\Exception $ex){
                return response()->json(['status'=>false,'message'=>config('constants.error')],500);
    
        } 
    
       
    }

}
