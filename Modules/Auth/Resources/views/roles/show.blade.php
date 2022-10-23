@extends('admin.layouts.master')

@section('title')
    {{-- {{ $page->title }} | @parent --}}
@stop

@section('page')
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="row">
          <div class="col-12">
            <div class="card my-4">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                  <h6 class="text-white text-capitalize ps-3">Show role</h6>

                </div>
               
              </div>
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <h6 class="mb-0 text-sm">{{$role->name}}</h6>
                  <p class="text-xs text-secondary mb-0">{{$role->display_name}}</p>
                  <p class="text-xs text-secondary mb-0">{{$role->description}}</p>
                  @if($role->status==1)
                  <span class="badge badge-sm bg-gradient-success">Active</span>
                  @elseif($role->status==0)
                      <span class="badge badge-sm bg-gradient-danger">InActive</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@stop