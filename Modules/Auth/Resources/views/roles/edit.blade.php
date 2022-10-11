
@extends('admin.layouts.master')
@section('page')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Role') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.update',$role->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                @if($role->name=="superadministrator")
                                <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $role->name }}" required autocomplete="name" autofocus disabled>
                                @else
                                <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $role->name }}" required autocomplete="name" autofocus>
                                @endif
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="display_name" class="col-md-4 col-form-label text-md-right">{{ __('Display Name') }}</label>

                            <div class="col-md-6">
                                <input id="display_name" type="display_name" class="form-control @error('display_name') is-invalid @enderror" name="display_name" value="{{ old('display_name') ?? $role->display_name }}" required autocomplete="display_name" autofocus>

                                @error('display_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('description') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="description" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') ?? $role->description }}" required autocomplete="current-description">

                                @error('description')
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
                                        <option value="{{$status}}" @if($status==$role->status) selected @endif>
                                            @if($status==1)
                                                Active
                                            @else
                                                InActive
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
                            <label for="permission" class="col-md-4 col-form-label text-md-right">{{ __('permission') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select my-select" id="choices-multiple-remove-button-in-edit" name="permissions[]" multiple>
                                    @foreach($permissions as $permission)
                                        <option value="{{$permission->id}}" @if(in_array($permission->id,$permissionsRole)) selected @endif  >{{$permission->name}}</option>
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
@stop