<?php

namespace Modules\Rule\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rule\Entities\Rule;
use DB;
class RuleController extends Controller
{
        public function __construct()
    {
         //    $this->middleware(['permission:rules_show'])->only('showRule');//admin, user
   
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('rule::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('rule::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('rule::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('rule::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    
    public function showRule($id){
        // dd($id);
        $rule=DB::table('rules')->get();
    //   $rule= Rule::where(['id'=>$id])->first();
      // dd($rule);
              if(is_string($rule)){
            // return response()->json(['status'=>false,'message'=>'rule not found'],404);
            return response()->json(['status'=>false,'message'=>'غير موجود بالنظام'],404);
        }
       
       return \response()->json([
            'code'=>200,
            'status'=>true,
            'message'=>'rule showed has been successfully',
            'data'=>$rule
        ]);
    }
}
