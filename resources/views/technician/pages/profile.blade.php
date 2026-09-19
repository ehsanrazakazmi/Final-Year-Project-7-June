{{-- @extends('layouts.technician')
@section('title', 'Edit Profile')
@section('content')
<h1 class="page-title">Edit Technician Profile</h1>
<div class="container" >
    <div class="text-end mb-5">
    </div>
    <div class="row">
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    <h5>Edit</h5>
                </div>
                <form action="{{ route('technicianpanel.pages.store') }}" method="post" role="form text-left" enctype="multipart/form-data">
                    @csrf
                <div class="card-body" >
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->name }}" type="text" placeholder="Name" id="user-name" name="name">
                                        @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('Email') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->email }}" type="email" placeholder="@example.com" id="user-email" name="email">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('Phone Number') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->ph_no }}" type="text" placeholder="03000000000" id="user-ph_no" name="ph_no">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user.phone" class="form-control-label">{{ __('Profile Image') }}</label>
                            <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                <input name="profile_photo_path" class="form-control" type="file" placeholder="upload your picture here" id="image" >
                                    @error('phone')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                        </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

@endsection --}}
@extends('layouts.technician')
@section('title', 'Edit Profile')
@section('content')
<h1 class="page-title">Edit Technician Profile</h1>
<div class="container" >
    <div class="text-end mb-5">
    </div>
    <div class="row">
        <div class="col-11">
            <div class="container">
            <div class="row mb-5">
            <div class="card">
                <div class="card-header">
                    <h5>Edit profile here</h5>
                </div>
                <form action="{{ route('technicianpanel.pages.store') }}" method="post" role="form text-left" enctype="multipart/form-data">
                    @csrf
                <div class="card-body" >
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->name }}" type="text" placeholder="Name" id="user-name" name="name">
                                        @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('Email') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->email }}" type="email" placeholder="@example.com" id="user-email" name="email">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('Phone Number') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->ph_no }}" type="text" placeholder="03000000000" id="user-ph_no" name="ph_no">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user.phone" class="form-control-label">{{ __('Profile Image') }}</label>
                            <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                <input name="profile_photo_path" class="form-control" type="file" placeholder="upload your picture here" id="image" >
                                    @error('phone')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                        </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>


@endsection