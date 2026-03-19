@extends('layouts.front.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Acasă</a></li>
                <li class="active">Comandă</li>
            </ol>
        </div>
    </div>

    @if(!$products->isEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                @include('layouts.errors-and-messages')
            </div>
        </div>
    </div>

    <form action="{{ route('guest-checkout.store') }}" method="POST" id="guest-checkout-form">
        {{ csrf_field() }}
        
        <div class="row">
            <!-- Customer Information -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> Informații client</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="customer_name">Nume complet *</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_email">Email *</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">Telefon *</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-home"></i> Adresa de livrare</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="address_1">Adresă *</label>
                            <input type="text" class="form-control" id="address_1" name="address_1" value="{{ old('address_1') }}" placeholder="Adresa străzii" required>
                        </div>
                        <div class="form-group">
                            <label for="address_2">Linia adresei 2</label>
                            <input type="text" class="form-control" id="address_2" name="address_2" value="{{ old('address_2') }}" placeholder="Apartament, suite, unitate, etc.">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">Oraș *</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zip">Cod poștal *</label>
                                    <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_code">Județ</label>
                                    <input type="text" class="form-control" id="state_code" name="state_code" value="{{ old('state_code') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country_id">Țară *</label>
                                    <select class="form-control" id="country_id" name="country_id" required>
                                        <option value="">Selectați țara</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Rezumat comandă</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Produs</th>
                                        <th>Cantitate</th>
                                        <th>Preț</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            @if(isset($product->options['image']))
                                                <img src="{{ asset('storage/'.$product->options['image']) }}" alt="{{ $product->name }}" style="width: 50px; margin-right: 10px;">
                                            @endif
                                            {{ $product->name }}
                                        </td>
                                        <td>{{ $product->qty }}</td>
                                        <td>{{ config('cart.currency_symbol') }} {{ number_format($product->price, 2) }}</td>
                                        <td>{{ config('cart.currency_symbol') }} {{ number_format($product->price * $product->qty, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                        <td>{{ config('cart.currency_symbol') }} {{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>TVA:</strong></td>
                                        <td>{{ config('cart.currency_symbol') }} {{ number_format($tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td><strong>{{ config('cart.currency_symbol') }} {{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-credit-card"></i> Metoda de plată</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Transfer bancar:</strong> {{ config('bank-transfer.description') }}
                        </div>
                        <p><small class="text-muted">* După plasarea comenzii, veți primi un email cu detaliile transferului bancar.</small></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Sigur doriți să plasați această comandă?')">
                    <i class="fa fa-check"></i> Plasează comanda
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="row">
        <div class="col-md-12">
            <p class="alert alert-warning">Coșul dvs. este gol. <a href="{{ route('home') }}">Continuați cumpărăturile</a></p>
        </div>
    </div>
    @endif
</div>
@endsection

