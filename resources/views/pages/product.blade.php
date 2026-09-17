@extends('layouts.master')
@section('name', '{{$product->title}}')
@section('content')
     @if(session()->has('addedToCart'))
         <section class="pop-up">
            <div class="pop-up-box">
                <div class="pop-up-title">
                {{session()->get('addedToCart')}}
            </div>
            <div class="pop-up-actions">
                <a href="{{route('home')}}" class="btn btn-outlined">Browse More servies...</a>
                <a href="{{route('cart')}}" class="btn btn-primary">order confirmation !</a>
            </div>
        </div>
         </section>
     @endif
    <div class="product-page">
        <div class="container">
            <div class="product-page-row">
                <section class="product-page-image">
                    <img src="{{asset('storage/'.$product->image)}}" alt="">
                </section>
                <section class="product-page-details">
                    <p class="p-title">{{$product->title}}</p>
                    <p class="p-price">Visit Charges : {{$product->price}}</p>
                    <p class="p-category">{{$product->category->name}}</p>
                    <p class="p-description">{{$product->description}}</p>
                    <form action="{{route('addToCart',$product->id)}}" method="post">
                        @csrf
                        <div class="p-form">
                            <div class="p-colors">
                                <label for="availability">Availability</label>
                                <select name="availability" id="availability" required>
                                    <option value="">-- Select Timing --</option>
                                    @foreach ($product->availabilities as $availability)
                                        <option value="{{$availability->id}}">{{$availability->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="p-quantity">
                                <label for="quantity">No. of Task</label>
                                <input type="number"name="quantity" min="1" max="100" value=1 required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-cart"> Order Now</button>
                    </form>

                </section>
            </div>
        </div>
    </div>
@endsection