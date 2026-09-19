@extends('layouts.technician')

@section('content')

<div>
    {{-- <div class="alert alert-secondary mx-4" role="alert">
        <span class="text-white">
            <strong>View the Orders to Change their status and update them to Transfer to Technicians...  here!</strong>
        </span>
    </div> --}}
    <h1 class="page-title">Show Order</h1>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 p-3">
                <div class="card-header pb-0">
                    
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id='myTable'>
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>name</th>
                                    <th>address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->name}}</td>
                                    <td>{{$order->address}}</td>
                                    <td>
                                        <span class="badge bg-@if($order->status == 'accepted')success
                                             @endif
                                            ">
                                            {{$order->status}}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{route('technicianpanel.status.update',$order->id)}}" method="post">
                                            @method('PUT')
                                            @csrf
                                           
                                            
                                            <div class="d-flex mt-2" >
                                                <button type="submit"  class="btn btn-secondary">Accept Order</button>
                                            </div>
                                        </form>
                                    </div>
                                    
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
 
@endsection




















































































{{-- @extends('layouts.technician')
@section('title','dashboard' )
@section('content')
    <h1 class="page-title">Show</h1>
    <div class="container">
        <div class="text-end mb-3">
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Orders</h5>
                    </div>
                    <div class="card-body">
                        @if(Session::has('order_message'))
                        <div class="alert alert-success" role="alert">{{Session::get('order_message')}}</div>
                        @endif
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>name</th>
                                    <th>email</th>
                                    <th>phone</th>
                                    <th>address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($order as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->name}}</td>
                                    <td>{{$order->email}}</td>
                                    <td>{{$order->phone}}</td>
                                    <td>{{$order->address}}</td>
                                
                                    <td>
                                        <span class="badge bg-@if($order->status =='shipped')warning
                                            @elseif($order->status == 'Accept')success @endif
                                             ">
                                        </span>
                                        
                                        {{$order->status}}
                                    </td>
                                    <td>
                                        <form action="{{route('technicianpanel.store',$order->id)}}" method="post">
                                            @csrf
                                            <select name="status" class="form-control">
                                                @foreach ($order as $status)
                                                <option value="{{$status}}">{{$status}}</option>
                                                
                                                @endforeach
                                            </select>
                                            
                                            <div class="form-group">
                                                <button type="submit">Accept Order</button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </td>
                                <td>
                                    <div class="d-flex" style="gap: 5px">
                                    <a href="{{route('technicianpanel.pages.view')}}" class="btn btn-secondary">View</a>

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

@endsection --}}