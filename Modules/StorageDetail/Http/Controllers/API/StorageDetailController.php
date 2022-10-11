<?php

namespace Modules\StorageDetail\Http\Controllers\API;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\StorageDetail\Entities\StorageDetail;
use Modules\StorageDetail\Http\Requests\StoreStorageDetailRequest;
use Modules\StorageDetail\Http\Requests\UpdateStorageDetailRequest;
use Modules\StorageDetail\Repositories\StorageDetailRepository;
class StorageDetailController extends Controller
{
      /**
* @var BaseRepository
*/
protected $baseRepo;
/**
* @var StorageDetailRepository
*/
protected $storageDetailRepo;
  /**
* @var StorageDetail
*/
protected $storageDetail;


/**
* StorageDetailsController constructor.
*
* @param StorageDetailRepository $storageDetails
*/
public function __construct(BaseRepository $baseRepo, StorageDetail $storageDetail,StorageDetailRepository $storageDetailRepo)
{
//   $this->middleware(['permission:storageDetails_store'])->only('store');
//   $this->middleware(['permission:storageDetails_update'])->only('update');
  $this->baseRepo = $baseRepo;
  $this->storageDetail = $storageDetail;
  $this->storageDetailRepo = $storageDetailRepo;
}



/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(StoreStorageDetailRequest $request)
{
  $storageDetail=$this->storageDetailRepo->store($request,$this->storageDetail);
  return response()->json([
      'status'=>true,
      'code' => 200,
      'message' => 'StorageDetail has been stored successfully',
      'data'=> $storageDetail
  ]);
}
/**
* Update the specified resource in storage.
*
* @param  int  $id
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function update(UpdateStorageDetailRequest $request,$id)
{
 $storageDetail= $this->storageDetailRepo->update($request,$id,$this->storageDetail);
  if(empty($storageDetail)){
     return response()->json([
         'status'=>false,
         'code' => 404,
         'message' => 'there is not exit this StorageDetail to update on it',
         'data'=> null
     ]);
 }else{
  return response()->json([
      'status'=>true,
      'code' => 200,
      'message' => 'StorageDetail has been updated successfully',
      'data'=> null
  ]);
 }

}
}
