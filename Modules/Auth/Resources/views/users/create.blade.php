
@extends('admin.layouts.master')
@section('page')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

ff        {{config('app.locale')}}
        
            <div class="card">
                <div class="card-header">{{ __('Create User') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('first_Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('last_Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="image" class="col-md-2 col-form-label">@lang('user image')</label>
                            <div class="col-md-10">
                                <input type="file" name="image"  id="user-image-in-create"  class="form-control" placeholder="{{ __('user_image') }}" />
                                <img id="user-preview" height="100px" width="100"/>
                            </div>
                        </div><!--form-group-->
                        <div class="form-group row">
                            <label for="passport_images" class="col-md-2 col-form-label">{!! trans('admin/users.Passport Image') !!}</label>
    
                            <div class="col-md-10">
                                <input multiple type="file"
                                 name="passport_images[]"
                                id="passport-images-in-create" 
                                class="form-control" 
                                placeholder="Passport Image" multiple />
                                <div id="passport-preview-in-create"></div>
                                
                            </div>
                        </div><!--form-group-->
                        <div class="form-group row">
                            <label for="phone_no1" class="col-md-4 col-form-label text-md-right">{{ __('phone_no1') }}</label>

                            <div class="col-md-6">
                                <input id="phone_no1" type="text" class="form-control @error('phone_no1') is-invalid @enderror" name="phone_no1" value="{{ old('phone_no1') }}" required autocomplete="phone_no1" autofocus>

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
                                <input id="phone_no2" type="text" class="form-control @error('phone_no2') is-invalid @enderror" name="phone_no2" value="{{ old('phone_no2') }}" required autocomplete="phone_no2" autofocus>

                                @error('phone_no2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
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
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select class="custom-select my-select" name="status">
                                    @foreach($statuses as $status)
                                        <option value="{{$status}}" >
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

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('role') }}</label>

                            <div class="col-md-6">
                                 <select class="custom-select my-select" id="choices-multiple-remove-button-in-edit" name="roles[]" multiple>
                                    @foreach($roles as $role)
                                        @if($role->name!=='superadministrator')
                                        <option value="{{$role->id}}"   >{{$role->name}}</option>
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
                                        <option value="{{$permission->id}}"  >{{$permission->name}}</option>
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
                                    {{ __('Create') }}
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
        
            
            let img_user = document.getElementById('user-image-in-create')
            let img_passport = document.getElementById('passport-images-in-create')
            img_user.addEventListener("change", (e)=>{
                let file = e.target.files[0]
                let reader = new FileReader()
                reader.addEventListener("load", (e)=>{
                    let user_preview_in_create = document.getElementById('user-preview')
                    user_preview_in_create.setAttribute("src", e.target.result)

                })
                reader.readAsDataURL(file)
            })
            
            img_passport.addEventListener("change", (e)=>{
                let files =  e.target.files
                let reader = new FileReader()
                files.forEach(file=>{
                    reader.addEventListener("load", (e)=>{
                    let passport_preview_in_create = 
                    document.getElementById('passport-preview-in-create')
                    let image = 
                    passport_preview_in_create.appendChild("")

                })
                    reader.readAsDataURL(file)
                })
                
            })


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

    window.onload= function(){
              new Choices('#choices-multiple-remove-button-in-edit', {
              removeItemButton: true,
              searchResultLimit:5,
              });
              new Choices('#permissions-select', {
              removeItemButton: true,
            //   maxItemCount:5,
              searchResultLimit:5,
            //   renderChoiceLimit: none 
              });
              
              
          };
   </script>
@stop