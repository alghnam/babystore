
@extends('admin.layouts.master')
@section('page')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit User') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update',$user->id) }}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('first_name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') ?? $user->profile->first_name }}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('last_name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') ?? $user->profile->last_name }}" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('image') }}</label>

                            <div class="col-md-6">
                                <input type="file" name="image"  id="user-image-in-edit"  class="form-control" placeholder="{{ __('user_image') }}" />
                                <img id="user-preview" height="100px" width="100"/>
                                {{-- <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image') }}" required autocomplete="image" autofocus> --}}
                                <img  id="user-preview-in-edit"  height="100px"src="{{asset('storage/' . $user->profile->image)}}" width="100"/></a>

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="passport_images" class="col-md-2 col-form-label">{!! trans('admin/users.Passport Image') !!}</label>
    
                            <div class="col-md-10">
                                <input type="file" name="passport_images[]"
                                 id="passport-images-in-edit" 
                                 class="form-control" 
                                 placeholder="{!! trans('admin/users.Passport Image') !!}" multiple />
                                 @foreach($user->profile->passportImages as $passportImage)
                                <a href="{{asset('storage/'.$passportImage->filename) }}" download>
                                    <img src="{{asset('storage/'.$passportImage->filename) }}" id="passport_preview" width="100px" height="100px"/>
                                </a>
                                @endforeach
                            </div>
                        </div><!--form-group-->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $user->email }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control
                                 @error('password') is-invalid @enderror" name="password" 
                                 value="{{ old('password') ?? $user->password }}" required autocomplete="current-password" 
                                >

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select class="custom-select my-select" name="status">
                                    @foreach($statuses as $status)
                                        <option value="{{$status}}" @if($status==$user->status) selected @endif>
                                            @if($status==0)
                                                InActive
                                            @else
                                                Active
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('country') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select"  name="country" id="country-select">
                                   <option value="" selected></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}"  {{ $country->id === $countryUser->id ? 'selected' : '' }} >{{$country->name}}</option>
                                    @endforeach
                                  </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('city') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select"  name="city"
                                 id="city city-select">
                                 @foreach($cities as $city)
                                     <option value="{{$city->id}}"  {{ $city->id === $cityUser->id ? 'selected' : '' }} >{{$city->name}}</option>
                                 @endforeach
                                  </select>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="town" class="col-md-4 col-form-label text-md-right">{{ __('town') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select"  name="town" id="town">
                                    @foreach($towns as $town)
                                        <option value="{{$town->id}}"  {{ $town->id === $townUser->id ? 'selected' : '' }} >{{$town->name}}</option>
                                    @endforeach
                                      
                                    
                                  </select>
                                @error('town')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="form-group row">
                            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('country') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select"  
                                 name="country" id="country-select">
                                   <option value="" selected></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}"   >{{$country->name}}</option>
                                    @endforeach
                                  </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('city') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select"  name="city"
                                 id="city">
                                 <option value="" selected></option>
                                  </select>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="town" class="col-md-4 col-form-label text-md-right">{{ __('town') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select"  name="town" id="town">
                                    <option value="" selected></option>
                                  </select>
                                @error('town')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="phone_no1" class="col-md-4 col-form-label text-md-right">{{ __('phone_no1') }}</label>

                            <div class="col-md-6">
                                <input id="phone_no1" type="text" class="form-control @error('phone_no1') is-invalid @enderror" name="phone_no1" value="{{ old('phone_no1') ?? $user->profile->phone_no1 }}" required autocomplete="phone_no1" autofocus>

                                @error('phone_no1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone_no2" class="col-md-4 col-form-label text-md-right">{{ __('phone_no2') }}</label>

                            <div class="col-md-6">
                                <input id="phone_no2" type="text" class="form-control @error('phone_no2') is-invalid @enderror" name="phone_no2" value="{{ old('phone_no2') ?? $user->profile->phone_no2 }}" required autocomplete="phone_no2" autofocus>

                                @error('phone_no2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('role') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select my-select" id="choices-multiple-remove-button-in-edit" name="roles[]" multiple>
                                    @foreach($roles as $role)
                                        @if($role->name!=='superadministrator')
                                        <option value="{{$role->id}}" @if(in_array($role->id,$rolesUser)) selected @endif  >{{$role->name}}</option>
                                        @endif
                                        @endforeach
                                  </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="permission" class="col-md-4 col-form-label text-md-right">{{ __('permission') }}</label>

                            <div class="col-md-6">                                 
                                <select class="custom-select my-select" id="permissions-select" name="permissions[]" multiple>
                                    @foreach($permissions as $permission)
                                        <option value="{{$permission->id}}" @if(in_array($permission->id,$permissionsUser)) selected @endif  >{{$permission->name}}</option>
                                    @endforeach
                                </select> 

                                @error('permission')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0 form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit') }}
                                </button>

                             
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
            
            let img_user = document.getElementById('user-image-in-edit')
            img_user.addEventListener("change", (e)=>{
                let file = e.target.files[0]
                let reader = new FileReader()
                reader.addEventListener("load", (e)=>{
                    let user_preview_in_edit = document.getElementById('user-preview-in-edit')
                    user_preview_in_edit.setAttribute("src", e.target.result)

                })
                reader.readAsDataURL(file)
            })
    
window.onload= function(){
      new Choices('#choices-multiple-remove-button-in-edit', {
      removeItemButton: true,
      searchResultLimit:5,
      });
      new Choices('#permissions-select', {
      removeItemButton: true,
      searchResultLimit:5,
      });

            //for selecting country to find cities this country

            let country = document.getElementById("country-select")
              let city_select = document.getElementById("city")
              country.addEventListener("change", (e)=>{

                  axios.get(`http://127.0.0.1:8000/admin/cities/cities-country/${e.target.value}`).then(res=>{
                      res.data.forEach(city=>{
                        let opt = document.createElement("option")

                          opt.innerHTML = city.name
                          opt.setAttribute("value", city.id)
                          city_select.appendChild(opt)
                      })
                  })
              })
//for selecting city to find towns this city
              let town_select = document.getElementById("town")
              city.addEventListener("change", (e)=>{
                  axios.get(`http://127.0.0.1:8000/admin/towns/towns-city/${e.target.value}`).then(res=>{
                      res.data.forEach(town=>{
              let town_opt = document.createElement("option")

                          town_opt.innerHTML = town.name
                          town_opt.setAttribute("value", town.id)
                          town_select.appendChild(town_opt)
                      })
                  })
              })
  };
</script>
@stop
