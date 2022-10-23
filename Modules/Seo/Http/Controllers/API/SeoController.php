<?php

namespace Modules\Seo\Http\Controllers\API;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Seo\Entities\Seo;
use Modules\Seo\Http\Requests\StoreSeoRequest;
use Modules\Seo\Http\Requests\UpdateSeoRequest;
use Modules\Seo\Repositories\SeoRepository;

class SeoController extends Controller
{
    /**
* @var BaseRepository
*/
protected $baseRepo;
/**
* @var SeoRepository
*/
protected $seoRepo;
  /**
* @var Seo
*/
protected $seo;


/**
* SeosController constructor.
*
* @param SeoRepository $seos
*/
public function __construct(BaseRepository $baseRepo, Seo $seo,SeoRepository $seoRepo)
{
//   $this->middleware(['permission:seos_store'])->only('store');
//   $this->middleware(['permission:seos_update'])->only('update');
  $this->baseRepo = $baseRepo;
  $this->seo = $seo;
  $this->seoRepo = $seoRepo;
}



/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(StoreSeoRequest $request)
{
  $seo=$this->seoRepo->store($request,$this->seo);
  return response()->json([
      'status'=>true,
      'code' => 200,
      'message' => 'Seo has been stored successfully',
      'data'=> $seo
  ]);
}
/**
* Update the specified resource in storage.
*
* @param  int  $id
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function update(UpdateSeoRequest $request,$id)
{
 $seo= $this->seoRepo->update($request,$id,$this->seo);
  if(empty($seo)){
     return response()->json([
         'status'=>false,
         'code' => 404,
         'message' => 'there is not exit this Seo to update on it',
         'data'=> null
     ]);
 }else{
  return response()->json([
      'status'=>true,
      'code' => 200,
      'message' => 'Seo has been updated successfully',
      'data'=> null
  ]);
 }

}
}
