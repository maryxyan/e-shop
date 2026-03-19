@extends('layouts.front.app')

@section('content')
<div class="container product-in-cart-list">
    @if(!$cartItems->isEmpty())
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Acasă</a></li>
                    <li class="active">Coș</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 content">
                <div class="box-body">
                    @include('layouts.errors-and-messages')
                </div>
                <h3><i class="fa fa-cart-plus"></i> Coșul de cumpărături</h3>
            </div>
        </div>

        <!-- Cart Header - Desktop -->
        <div class="row hidden-xs hidden-sm cart-header">
            <div class="col-md-2"><strong>Copertă</strong></div>
            <div class="col-md-5"><strong>Denumire</strong></div>
            <div class="col-md-2"><strong>Cantitate</strong></div>
            <div class="col-md-1"><strong>Șterge</strong></div>
            <div class="col-md-2 text-right"><strong>Preț</strong></div>
        </div>

        <!-- Cart Items -->
        @foreach($cartItems as $cartItem)
            <div class="row cart-item">
                <div class="col-md-2 col-sm-3 col-xs-4">
                    <a href="{{ route('front.get.product', [$cartItem->product->slug]) }}" class="hover-border">
                        @if(isset($cartItem->cover))
                            <img src="{{$cartItem->cover}}" alt="{{ $cartItem->name }}" class="img-responsive img-thumbnail">
                        @else
                            <img src="https://placehold.it/120x120" alt="" class="img-responsive img-thumbnail">
                        @endif
                    </a>
                </div>
                <div class="col-md-5 col-sm-9 col-xs-8">
                    <h4 style="margin-bottom:5px;">{{ $cartItem->name }}</h4>
                    @if($cartItem->options->has('combination'))
                        <div style="margin-bottom:5px;">
                            @foreach($cartItem->options->combination as $option)
                                <small class="label label-primary">{{$option['value']}}</small>
                            @endforeach
                        </div>
                    @endif
                    <div class="product-description">
                        {!! $cartItem->product->description !!}
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <form action="{{ route('cart.update', $cartItem->rowId) }}" class="form-inline" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="input-group">
                            <input type="text" name="quantity" value="{{ $cartItem->qty }}" class="form-control input-sm" />
                            <span class="input-group-btn"><button class="btn btn-default btn-sm">Actualizează</button></span>
                        </div>
                    </form>
                </div>
                <div class="col-md-1 col-sm-6 col-xs-12"> 
                    <form action="{{ route('cart.destroy', $cartItem->rowId) }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="delete">
                        <button onclick="return confirm('Sigur doriți să ștergeți?')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                    </form>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12 text-right">
                    <strong>{{config('cart.currency_symbol')}} {{ number_format($cartItem->price, 2) }}</strong>
                    <div class="visible-xs visible-sm">
                        <small>Total: {{config('cart.currency_symbol')}} {{ number_format(($cartItem->qty*$cartItem->price), 2) }}</small>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach

        <!-- Cart Totals -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tfoot>
                        <tr>
                            <td class="bg-warning">Subtotal</td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning text-right">{{config('cart.currency_symbol')}} {{ number_format($subtotal, 2, '.', ',') }}</td>
                        </tr>
                        @if(isset($shippingFee) && $shippingFee != 0)
                        <tr>
                            <td class="bg-warning">Livrare</td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning text-right">{{config('cart.currency_symbol')}} {{ $shippingFee }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="bg-warning">TVA</td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"></td>
                            <td class="bg-warning text-right">{{config('cart.currency_symbol')}} {{ number_format($tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="bg-success">Total</td>
                            <td class="bg-success"></td>
                            <td class="bg-success"></td>
                            <td class="bg-success"></td>
                            <td class="bg-success text-right">{{config('cart.currency_symbol')}} {{ number_format($total, 2, '.', ',') }}</td>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group pull-right">
                            <a href="{{ route('home') }}" class="btn btn-default">Continuă cumpărăturile</a>
                            <a href="{{ route('guest-checkout.index') }}" class="btn btn-warning">Finalizare ca oaspete</a>
                            @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary">Finalizare cu cont</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <p class="alert alert-warning">Nu aveți produse în coș. <a href="{{ route('home') }}">Cumpără acum!</a></p>
            </div>
        </div>
    @endif
</div>
@endsection
@section('css')
    <style type="text/css">
        .product-description {
            padding: 10px 0;
        }
        .product-description p {
            line-height: 18px;
            font-size: 14px;
        }
        .cart-header {
            background-color: #f5f5f5;
            padding: 10px 0;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .cart-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection
