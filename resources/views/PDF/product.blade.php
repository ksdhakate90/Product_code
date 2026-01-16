<h2>{{ $product->name }}</h2>
<p>{{ $product->description }}</p>

<hr>

@foreach($product->brands as $brand)
    <h4>{{ $brand->brand_name }}</h4>
    <img src="{{ public_path('storage/'.$brand->image) }}" width="100">
    <p>Price: {{ $brand->price }}</p>
    <hr>
@endforeach

<h3>Total Price: {{ $totalPrice }}</h3>