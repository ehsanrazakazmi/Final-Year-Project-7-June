@extends('layouts.technician')

@section('content')

<div>
    <h1 class="page-title">Show Order</h1>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 p-3">
                <div class="card-header pb-0">
                    
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="myTable">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $order)
                                    @if ($order->status == 'accepted')
                                        <tr>
                                            <td>{{$order->id}}</td>
                                            <td>{{$order->status}}</td>
                                            <td>{{\Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s')}}</td>
                                        </tr>
                                    @endif
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
