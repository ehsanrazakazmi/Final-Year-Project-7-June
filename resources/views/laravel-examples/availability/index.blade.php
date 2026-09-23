@extends('layouts.user_type.auth')

@section('content')

<div>

    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Edit Availability</h1>
<div class="container" >
    <div class="row mb-5">
        <div class="col-md-6 offser-md-3">
            <div class="card" style="background-color: rgb(172, 245, 221)">
                <div class="card-header" style="background-color: rgb(172, 245, 221)">
                    <h5>Create Timing (Availibility)</h5>
                </div>
                <div class="card-body" >
                    <form action="{{route('adminpanel.availability.store')}}" method="post"> 
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
                            @error('name')
                            <span class="invalid-feedback">
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror 
                        </div>
                        <div class="form-group mb-3">
                            <label for="code">Available From</label>
                            <input type="time" name="available_from" id="available_from" class="form-control @error('available_from') is-invalid @enderror" value="{{old('available_from')}}">
                            @error('available_from')
                            <span class="invalid-feedback">
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror 
                        </div>
                        <div class="form-group mb-3">
                            <label for="code1">Available To</label>
                            <input type="time" name="available_to" id="available_to" class="form-control @error('available_to') is-invalid @enderror" value="{{old('available_to')}}">
                            @error('available_to')
                            <span class="invalid-feedback">
                                <strong>{{$message}}</strong>
                            </span>
                            @enderror 
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color: rgb(172, 245, 221)">
                    <h5>Availibility</h5>
                </div>
                <div class="card-body" style="background-color: rgb(172, 245, 221)">
                    <table class="table table-stripped" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Published</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($availabilities as $availability)
                                
                           
                            <tr>
                                <td>{{$availability->id}}</td>
                                <td>{{$availability->name}}</td>
                                <td>
                                    <div style="display: flex; align-items:center; gap:10px">
                                    {{$availability->available_from}} <span style="display: inline-block; width:30px; border-radius:50%; height:30px; background: {{$availability->available_from}};"></span>
                                </div>
                                </td>

                                <td>{{$availability->available_to}}</td>
                                
                                <td>{{\Carbon\Carbon::parse($availability->created_at)->format('d/m/Y')}}</td>
                                <td>
                                    <form action="{{route('adminpanel.availability.destroy', $availability->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
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
    </div>
</div>
 
@endsection