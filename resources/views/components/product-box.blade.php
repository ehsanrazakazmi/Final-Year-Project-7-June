<section  class="product-box">
    <div class="image">
        <img src="{{asset('storage/' . $product->image)}}" alt="N/A">
        @auth
            @if (auth()->user()->wishlist->contains($product))
                <form action="{{route('removeFromWishlist', $product->id)}}"method='post'>
                    @csrf
                    <button class="add-to-wishlist" type="submit">Remove From Wishlist</button>
                </form>               
            @else
                <form action="{{route('addToWishlist', $product->id)}}"method='post'>
                    @csrf
                    <button class="add-to-wishlist" type="submit">Add to Wishlist</button>
                </form>                 
            @endif
        @endauth
    </div>
    <a href="{{route('product', $product->id)}}">
        <div class="product-title">{{$product->title}}</div>
        <div class="product-title">
           {{$product->category->name}}
        </div>
     <div class="color-plateletes">
        @foreach ($product->availabilities as $availability)    
        <div >
                {{$availability->available_from}} to {{$availability->available_to}} 
        </div>
        <div style="clear: both">

        </div>

        @endforeach
     </div>
     <div class="product-category"></div>
     <div class="product-price">Rs {{$product->price}}</div>
    </a>
     
    </section>

