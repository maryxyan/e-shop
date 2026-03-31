@if (!empty($products) && !collect($products)->isEmpty())
    <ul class="row text-center list-unstyled product-grid">
        @foreach ($products as $product)
            <li class="col-md-3 col-sm-6 col-xs-12 product-list">
                <div class="single-product">
                    <div class="product">
                        <div class="product-overlay">
                            <div class="vcenter">
                                <div class="centrize">
                                    <ul class="list-unstyled list-group">
                                        <li>
                                            <button type="button" class="btn btn-warning quick-view-btn" data-toggle="modal"
                                                data-target="#myModal_{{ $product->id }}"> <i class="fa fa-eye"></i>
                                                Vizualizare rapidă</button>
                                        </li>
                                        <li>
                                            <a class="btn btn-default product-btn" style="font-size:13px;"
                                                href="{{ route('front.get.product', $product->slug) }}"> <i
                                                    class="fa fa-link"></i> Vezi produsul</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if (isset($product->cover))
                            <img src="{{ $product->cover }}" alt="{{ $product->name }}"
                                class="img-bordered img-responsive category-cover-img">
                        @else
                            <img src="{{ asset('images/NoData.png') }}" alt="{{ $product->name }}"
                                class="img-bordered img-responsive category-cover-img">
                        @endif
                    </div>

                    <div class="product-text">
                        <div class="product-card-body">
                            <h4>{{ $product->name }}</h4>
                            <p>
                                {{ config('cart.currency') }}
                                @if (!is_null($product->attributes->where('default', 1)->first()))
                                    @if (!is_null($product->attributes->where('default', 1)->first()->sale_price))
                                        {{ number_format($product->attributes->where('default', 1)->first()->sale_price, 2) }}
                                        <span class="text text-danger product-card-sale">Sale!</span>
                                    @else
                                        {{ number_format($product->attributes->where('default', 1)->first()->price, 2) }}
                                    @endif
                                @else
                                    {{ number_format($product->price, 2) }}
                                @endif
                            </p>
                        </div>
                        <form action="{{ route('cart.store') }}" class="product-card-add-form" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="quantity" value="1" />
                            <input type="hidden" name="product" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-warning product-card-add-btn btn-block"
                                data-toggle="modal" data-target="#cart-modal">
                                <i class="fa fa-cart-plus"></i> Adaugă în coș
                            </button>
                        </form>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal_{{ $product->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                @php
                                    $images = $product->images()->get();
                                    $productAttributes = $product->attributes;
                                @endphp
                                @include('layouts.front.product', compact('product', 'images', 'productAttributes'))
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    @if ($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">{{ $products->links() }}</div>
            </div>
        </div>
    @endif
@else
    <p class="alert alert-warning">No products yet.</p>
@endif
