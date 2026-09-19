{{-- @extends('layouts.technician')

@section('content')

<div>
    <h1 class="page-title">Show</h1>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 p-3">
                <div class="card-header pb-0">

                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                    Name: {{Auth::user()->name}}
                    Email: {{Auth::user()->email}}
                </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

@extends('layouts.technician')
@section('title', 'confirmed')
@section('content')
<h1 class="page-title">Technician Profile</h1>
<div class="container">
    <div class="text-end mb-5">
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Technician Profile Picture</h5>
                </div>
                <div class="card-body">
                    @if (Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-100 border-radius-lg shadow-sm">
                    @else
                        <p class="text-sm text-secondary mb-0">No profile photo uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5>Technician Information</h5>
                </div>
                <div class="card-body">
                    <p class="text-primary">Name: {{Auth::user()->name}}</p>
                    <p>Email: {{Auth::user()->email}}</p>
                    <p>Phone Number: {{Auth::user()->ph_no}}</p>
                    <div class="d-flex mt-2">
                        <a href="{{route('technicianpanel.pages.profile')}}"><button class="btn btn-primary">Edit Profile</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection