@extends('layouts.master')
@section('name', 'Account Page')
@section('content')
    <div class="account-page">
        <div class="container">
          <section class="u-box">
            <div class="user-info">
              <p class="user-name">
                {{Auth::user()->name}}
              </p>
              <p class="user-email">
                {{Auth::user()->email}}
              </p>
              <p class="user-ph_no">
                {{Auth::user()->ph_no}}
              </p>
              @if (Auth::user()->profile_photo_path)
                  <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                @endif
            </div>
            
            <div class="user-btn">
              <form action="{{route('logout')}}" method="post">
                  @csrf
                  <button class="btn btn-primary">logout</button>
                </form> 
                <a href="{{route('pages.profile')}}"><button class="btn btn-primary">Edit Profile</button></a>
            </div>
          </section>
          <section class="orders-box">
            <p class="orders-box-title">Orders</p>
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Items</th>
                  <th>Total</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @if (auth()->user()->orders && auth()->user()->orders->count())
                @foreach (auth()->user()->orders as $order)
                <tr>
                  <td>{{$order->id}}</td>
                  <td>{{$order->items->count()}}</td>
                  <td>{{ "Rs " . number_format($order->total) }}</td>
                  <td>{{\Carbon\Carbon::parse($order->created_at)->format('d/m/Y')}}</td>
                  <td>{{$order->status}}</td>
                </tr>   
                @endforeach 
                @else
                <tr>
                  <td colspan="5" style="text-align: center">No Orders...</td>
                </tr>                  
                @endif
                
              </tbody>
            </table>
          </section>
            
             
         
        </div>
    </div>
@endsection