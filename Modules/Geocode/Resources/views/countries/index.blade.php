@extends('admin.layouts.master')

@section('title')
    {{-- {{ $page->title }} | @parent --}}
@stop

@section('page')
    <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12">
            <div class="card my-4">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                  <h6 class="text-white text-capitalize ps-3">countries Management</h6>

                </div>
                <a href="{{ route('admin.countries.create') }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Create city">
                    Create
                  </a>
                  <a href="{{ route('admin.countries.trash') }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="view countries in trash">
                    view countries in trash
                  </a>
              </div>
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">code</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">country</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($countries as $country)
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div>
                              <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="country1">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm">{{$country->name}}</h6>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$country->code}}</p>
                        </td>

                        <td class="align-middle text-center text-sm">
                            @if($country->status==1)
                                <span class="badge badge-sm bg-gradient-success">Active</span>
                            @elseif($country->status==0)
                                <span class="badge badge-sm bg-gradient-danger">InActive</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">{{$country->created_at}}</span>
                        </td>
                        <td class="align-middle">
                          <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit country">
                            <a href="{{ route('admin.countries.destroy',$country->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete country">
                            Delete
                          </a>
                          <a href="{{ route('admin.countries.edit',$country->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit country">
                            Edit
                          </a>
                          <a href="{{ route('admin.countries.show',$country->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Show country">
                            show
                          </a>
                         
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@stop