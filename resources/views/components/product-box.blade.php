<section class="product-box">
    <div class="image">
        {{-- Services uploaded without an image rendered a broken-image icon.
             Fall back to a placeholder instead. --}}
        @php $img = $product->image ? asset('storage/'.$product->image) : null; @endphp

        @if ($img)
            <img src="{{ $img }}" alt="{{ $product->title }}"
                 onerror="this.closest('.image').classList.add('no-image'); this.remove();">
        @else
            <span class="image-placeholder" aria-hidden="true"></span>
        @endif

        @auth
            @if (auth()->user()->wishlist->contains($product))
                <form action="{{ route('removeFromWishlist', $product->id) }}" method="post">
                    @csrf
                    <button class="add-to-wishlist" type="submit">Remove from wishlist</button>
                </form>
            @else
                <form action="{{ route('addToWishlist', $product->id) }}" method="post">
                    @csrf
                    <button class="add-to-wishlist" type="submit">Add to wishlist</button>
                </form>
            @endif
        @endauth
    </div>

    <a href="{{ route('product', $product->id) }}" class="product-body">
        <div class="product-title">{{ $product->title }}</div>

        {{-- The category used to reuse .product-title, so it rendered in the
             same colour and weight as the service name, and the real
             .product-category div was left empty. --}}
        <div class="product-category">{{ $product->category->name ?? '' }}</div>

        @if ($product->availabilities->isNotEmpty())
            <ul class="product-availability">
                @foreach ($product->availabilities as $availability)
                    <li>{{ $availability->available_from }} &ndash; {{ $availability->available_to }}</li>
                @endforeach
            </ul>
        @endif

        <div class="product-price">Rs {{ number_format($product->price) }}</div>
    </a>
</section>
