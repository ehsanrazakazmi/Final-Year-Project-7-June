@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="alert alert-secondary mx-4" role="alert">
        <span class="text-white">
            <strong>View the Orders to Change their status and update them to Transfer to Technicians...  here!</strong>
        </span>
    </div>

    <div class="row">
        <div class="col-12" >
            <div class="card mb-4 mx-4 p-3" style="background-color: rgb(172, 245, 221)">
                <div class="card-header pb-0">
                    
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id = 'myTable'>
                            <thead>
                                <tr style="font-style: oblique">
                                    <th>Orders ID</th>
                                    <th>By</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->name}}</td>
                                    <td>3</td>
                                    <td>{{ "Rs " . number_format($order->total) }}</td>
                                    <td>{{\Carbon\Carbon::parse($order->created_at)->format('d/m/Y')}}</td>
                                    <td>
                                        <span class="badge bg-@if($order->status == 'pending')warning
                                            @elseif($order->status == 'processing')info
                                            @elseif($order->status == 'shipped')success
                                            @elseif($order->status == 'cancelled')danger @endif
                                            " >
                                            {{$order->status}}
                                        </span>
                                    </td>
                                    <td>
                                         <div class="d-flex mt-2" style="gap: 5px">
                                            <a href="{{route('orders.view',$order->id)}}" class="btn btn-secondary">View</a>
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



{{-- @extends('layouts.admin')
@section('title','Orders')
@section('content')
    <h1 class="page-title">Orders</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Orders</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>Orders ID</th>
                                    <th>By</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->user->name}}</td>
                                    <td>{{$order->items->count()}}</td>
                                    <td>{{ "Rs " . number_format($order->total) }}</td>
                                    <td>{{\Carbon\Carbon::parse($order->created_at)->format('d/m/Y')}}</td>
                                    <td>
                                        <span class="badge bg-@if($order->status == 'pending')warning
                                            @elseif($order->status == 'processing')info
                                            @elseif($order->status == 'shipped')success
                                            @elseif($order->status == 'cancelled')danger @endif
                                            " >
                                            {{$order->status}}
                                        </span>
                                    </td>
                                    <td>
                                         <div class="d-flex" style="gap: 5px">
                                            <a href="{{route('orders.view',$order->id)}}" class="btn btn-secondary">View</a>
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

    
@endsection --}}