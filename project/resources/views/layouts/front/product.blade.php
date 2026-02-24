<!-- Product Gallery -->
<div class="row">
    <div class="col-md-6">
        <div class="product-gallery">
            <!-- Main Image -->
            <div class="main-image-container">
                @if (!empty($product->cover))
                    <img id="main-image" class="img-responsive product-main-image" 
                         src="{{ $product->cover }}" 
                         alt="{{ $product->name }}"
                         data-zoom-image="{{ $product->cover }}">
                @else
                    <img id="main-image" class="img-responsive product-main-image" 
                         src="{{ asset('images/NoData.png') }}" 
                         alt="{{ $product->name }}">
                @endif
            </div>
            <!-- Thumbnails -->
            @if (isset($images) && !$images->isEmpty())
                <div class="product-thumbnails">
                    <ul class="thumbnails-list list-unstyled">
                        <li class="thumbnail-item active">
                            <a href="javascript:void(0)" data-image="{{ $product->cover }}">
                                <img class="img-responsive" src="{{ $product->cover }}" alt="{{ $product->name }}">
                            </a>
                        </li>
                        @foreach ($images as $image)
                            <li class="thumbnail-item">
                                <a href="javascript:void(0)" data-image="{{ asset('storage/'.$image->src) }}">
                                    <img class="img-responsive" src="{{ asset('storage/'.$image->src) }}" alt="{{ $product->name }}">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-md-6">
        <div class="product-details">
            <!-- Product Name -->
            <h1 class="product-title">{{ $product->name }}</h1>
            
            <!-- SKU and Brand -->
            <div class="product-meta">
                @if(isset($product->sku))
                <span class="product-sku">SKU: {{ $product->sku }}</span>
                @endif
                @if(isset($product->brand) && $product->brand)
                <span class="product-brand">Brand: {{ $product->brand->name }}</span>
                @endif
            </div>

            <!-- Price -->
            <div class="product-price-block">
                @if (isset($productAttributes) && !$productAttributes->isEmpty())
                    @php $defaultAttr = $productAttributes->where('default', 1)->first(); @endphp
                    @if($defaultAttr && !is_null($defaultAttr->sale_price))
                        <span class="original-price">{{ config('cart.currency') }} {{ number_format($defaultAttr->price, 2) }}</span>
                        <span class="sale-price">{{ config('cart.currency') }} {{ number_format($defaultAttr->sale_price, 2) }}</span>
                        <span class="discount-badge">REDUCERE {{ number_format((($defaultAttr->price - $defaultAttr->sale_price) / $defaultAttr->price) * 100, 0) }}%</span>
                    @elseif($defaultAttr)
                        <span class="current-price">{{ config('cart.currency') }} {{ number_format($defaultAttr->price, 2) }}</span>
                    @else
                        <span class="current-price">{{ config('cart.currency') }} {{ number_format($product->price, 2) }}</span>
                    @endif
                @else
                    @if($product->sale_price)
                        <span class="original-price">{{ config('cart.currency') }} {{ number_format($product->price, 2) }}</span>
                        <span class="sale-price">{{ config('cart.currency') }} {{ number_format($product->sale_price, 2) }}</span>
                        <span class="discount-badge">REDUCERE {{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%</span>
                    @else
                        <span class="current-price">{{ config('cart.currency') }} {{ number_format($product->price, 2) }}</span>
                    @endif
                @endif
            </div>

            <!-- Stock Status -->
            <div class="stock-status">
                @if($product->quantity > 0)
                    <span class="in-stock"><i class="fa fa-check-circle"></i> În Stoc</span>
                @else
                    <span class="out-of-stock"><i class="fa fa-times-circle"></i> Indisponibil</span>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->description)
            <div class="product-short-description">
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 200) }}</p>
            </div>
            @endif

            <hr>

            <!-- Add to Cart Form -->
            @include('layouts.errors-and-messages')
            <form action="{{ route('cart.store') }}" class="product-add-to-cart" method="post">
                {{ csrf_field() }}
                
                <!-- Product Attributes/Variants -->
                @if (isset($productAttributes) && !$productAttributes->isEmpty())
                    <div class="product-attributes">
                        <label>Opțiune:</label>
                        <select name="productAttribute" id="productAttribute" class="form-control">
                            @foreach ($productAttributes as $productAttribute)
                                <option value="{{ $productAttribute->id }}" {{ $productAttribute->default == 1 ? 'selected' : '' }}>
                                    @foreach ($productAttribute->attributesValues as $value)
                                        {{ ucwords($value->value) }}
                                    @endforeach
                                    @if (!is_null($productAttribute->sale_price))
                                        - {{ config('cart.currency') }} {{ number_format($productAttribute->sale_price, 2) }}
                                    @elseif(!is_null($productAttribute->price))
                                        - {{ config('cart.currency') }} {{ number_format($productAttribute->price, 2) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Quantity and Add to Cart -->
                <div class="add-to-cart-section">
                    <div class="quantity-selector">
                        <label>Cantitate:</label>
                        <div class="quantity-input-group">
                            <button type="button" class="qty-btn qty-minus" onclick="updateQty(-1)">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity ?? 99 }}">
                            <button type="button" class="qty-btn qty-plus" onclick="updateQty(1)">+</button>
                        </div>
                    </div>
                    <input type="hidden" name="product" value="{{ $product->id }}" />
                    <button type="submit" class="btn btn-primary btn-add-to-cart">
                        <i class="fa fa-shopping-cart"></i> Adaugă în Coș
                    </button>
                </div>
            </form>

            <!-- Shipping Info -->
            <div class="shipping-info">
                <div class="shipping-item">
                    <i class="fa fa-truck"></i>
                    <span>Livrare rapidă 24-48 ore</span>
                </div>
                <div class="shipping-item">
                    <i class="fa fa-shield"></i>
                    <span>Garanție 2 ani</span>
                </div>
                <div class="shipping-item">
                    <i class="fa fa-undo"></i>
                    <span>Retur în 14 zile</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Specifications -->
@if($product->description)
<div class="row product-specifications">
    <div class="col-md-12">
        <div class="specifications-section">
            <h3>Descriere Produs</h3>
            <div class="description">{!! $product->description !!}</div>
        </div>
    </div>
</div>
@endif

<!-- Related Products -->
@if(isset($relatedProducts) && !$relatedProducts->isEmpty())
<div class="row related-products">
    <div class="col-md-12">
        <div class="related-products-section">
            <h3>Produse Asemănătoare</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 col-sm-6">
                        <div class="related-product-item">
                            <a href="{{ route('front.get.product', $relatedProduct->slug) }}">
                                @if($relatedProduct->cover)
                                    <img src="{{ $relatedProduct->cover }}" alt="{{ $relatedProduct->name }}" class="img-responsive">
                                @else
                                    <img src="{{ asset('images/NoData.png') }}" alt="{{ $relatedProduct->name }}" class="img-responsive">
                                @endif
                                <h4>{{ $relatedProduct->name }}</h4>
                                <span class="price">{{ config('cart.currency') }} {{ number_format($relatedProduct->price, 2) }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<style>
.product-gallery {
    margin-bottom: 20px;
}
.main-image-container {
    border: 1px solid #eee;
    padding: 10px;
    margin-bottom: 15px;
    text-align: center;
}
.product-main-image {
    max-width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
}
.thumbnails-list {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}
.thumbnail-item {
    width: 80px;
    height: 80px;
    border: 2px solid #eee;
    cursor: pointer;
    padding: 5px;
    transition: all 0.3s;
}
.thumbnail-item:hover, .thumbnail-item.active {
    border-color: #337ab7;
}
.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.product-details {
    padding: 20px 0;
}
.product-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}
.product-meta {
    margin-bottom: 15px;
    font-size: 14px;
    color: #666;
}
.product-meta span {
    margin-right: 20px;
}
.product-price-block {
    margin-bottom: 15px;
}
.product-price-block .original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 16px;
    margin-right: 10px;
}
.product-price-block .sale-price, 
.product-price-block .current-price {
    font-size: 24px;
    font-weight: bold;
    color: #e74c3c;
}
.discount-badge {
    background: #e74c3c;
    color: white;
    padding: 3px 8px;
    font-size: 12px;
    border-radius: 3px;
    margin-left: 10px;
}
.stock-status {
    margin-bottom: 15px;
}
.stock-status .in-stock {
    color: #27ae60;
    font-weight: bold;
}
.stock-status .out-of-stock {
    color: #e74c3c;
    font-weight: bold;
}
.product-short-description {
    margin-bottom: 20px;
    color: #666;
}
.add-to-cart-section {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    margin-bottom: 20px;
}
.quantity-selector label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
.quantity-input-group {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}
.quantity-input-group input {
    width: 60px;
    text-align: center;
    border: none;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
}
.qty-btn {
    width: 35px;
    height: 35px;
    border: none;
    background: #f5f5f5;
    cursor: pointer;
    font-size: 18px;
}
.qty-btn:hover {
    background: #e0e0e0;
}
.btn-add-to-cart {
    padding: 10px 30px;
    font-size: 16px;
    background: #337ab7;
    border-color: #337ab7;
}
.btn-add-to-cart:hover {
    background: #286090;
    border-color: #286090;
}
.shipping-info {
    margin-top: 20px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 5px;
}
.shipping-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    color: #666;
}
.shipping-item i {
    width: 25px;
    color: #337ab7;
}
.shipping-item:last-child {
    margin-bottom: 0;
}
.specifications-section {
    margin-top: 40px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 5px;
}
.specifications-section h3 {
    margin-bottom: 15px;
    color: #333;
}
.related-products-section {
    margin-top: 40px;
}
.related-products-section h3 {
    margin-bottom: 20px;
    color: #333;
}
.related-product-item {
    text-align: center;
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    transition: all 0.3s;
}
.related-product-item:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.related-product-item img {
    max-height: 150px;
    object-fit: contain;
    margin-bottom: 10px;
}
.related-product-item h4 {
    font-size: 14px;
    margin-bottom: 5px;
    color: #333;
}
.related-product-item .price {
    color: #e74c3c;
    font-weight: bold;
}
</style>

@section('js')
<script type="text/javascript">
    // Thumbnail click handler
    $(document).ready(function() {
        $('.thumbnail-item a').on('click', function() {
            var newImage = $(this).data('image');
            $('#main-image').attr('src', newImage);
            $('.thumbnail-item').removeClass('active');
            $(this).parent().addClass('active');
        });
    });

    // Quantity update function
    function updateQty(change) {
        var input = document.getElementById('quantity');
        var newValue = parseInt(input.value) + change;
        var min = parseInt(input.getAttribute('min')) || 1;
        var max = parseInt(input.getAttribute('max')) || 99;
        
        if (newValue >= min && newValue <= max) {
            input.value = newValue;
        }
    }
</script>
@endsection
