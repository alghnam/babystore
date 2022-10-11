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
                  <h6 class="text-white text-capitalize ps-3">{{trans('admin/users.Users Management')}}</h6>

                </div>
                <a href="{{ url('admin/users/create') }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Create user">
                    {{trans('admin/users.Create')}}
                  </a>
                  <a href="{{ route('admin.users.trash') }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="view users in trash">
                    {{trans('admin/users.view users in trash')}}
                  </a>
              </div>
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{trans('admin/users.Name/Email')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Password')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.First Name')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Last Name')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Image')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Country')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.City')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Town')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Phone No1.')}}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{trans('admin/users.Phone No2.')}}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{trans('admin/users.Status')}}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{trans('admin/users.Created At')}}</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div>
                              <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                            </div>
                            <div class="d-flex flex-column justify-content-center">

                              <h6 class="mb-0 text-sm">{{$user->name}}</h6>
                              {{-- aaaa {{$user->translate('en')->name}} --}}
                              <p class="text-xs text-secondary mb-0">{{$user->email}}</p>
                            </div>
                          </div>
                        </td>
                        <td>

                          {{-- <p class="text-xs font-weight-bold mb-0">{{Crypt::decryptString($user->password)}}</p> --}}
                          <p class="text-xs font-weight-bold mb-0">{{$user->password}}</p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile?$user->profile->first_name:null}}</p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile?$user->profile->last_name:null}}</p>
                        </td>
                        
                        <td>
                          {{-- <img src="{{$user->profile?{{asset('storage/' $user->profile->image_path)}}:null}}"> --}}
                          @if($user->profile&&$user->profile->image&&$user->profile->image->url)
                          alaaa : {{asset($user->profile->image->url)}}
                          {{-- <img src="{{asset('storage/' . $user->profile->image)}}" width="100"> --}}
                          <img src="{{asset('storage/' . $user->profile->image->thumbnail)}}" width="100">
                          @endif
                        </td>
 
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile&&$user->profile->country?$user->profile->country->name:null}}</p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile&&$user->profile->city?$user->profile->city->name:null}}</p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile&&$user->profile->town?$user->profile->town->name:null}}</p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile?$user->profile->phone_no1:null}}</p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">{{$user->profile?$user->profile->phone_no2:null}}</p>
                        </td>
                        <td class="align-middle text-center text-sm">
                            @if($user->status==1)
                                <span class="badge badge-sm bg-gradient-success">{{trans('admin/users.Active')}}</span>
                            @elseif($user->status==0)
                                <span class="badge badge-sm bg-gradient-danger">{{trans('admin/users.InActive')}}</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">{{$user->created_at}}</span>
                        </td>
                        <td class="align-middle">
                          <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                            <a href="{{ route('admin.users.destroy',$user->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete user">
                            {{trans('admin/users.Delete')}}
                          </a>
                          <a href="{{ route('admin.users.edit',$user->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                            {{trans('admin/users.Edit')}}
                          </a>
                          <a href="{{ route('admin.users.show',$user->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Show user">
                            {{trans('admin/users.Show')}}
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
