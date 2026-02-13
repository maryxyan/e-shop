{{-- Top header: 3 bars like reference image (desktop) + mobile nav --}}
@php
    $warehouse = config('shop.warehouse', []);
    $addressLine = trim(implode(', ', array_filter([
        'Showroom ' . config('shop.name'),
        $warehouse['address_1'] ?? '',
        $warehouse['city'] ?? '',
        $warehouse['country'] ?? ''
    ])));
@endphp

{{-- Bar 1: Utility bar (dark grey) - contact left, social right --}}
<div class="header-utility-bar hidden-xs">
    <div class="container">
        <div class="header-utility-inner">
            <ul class="header-utility-left">
                <li><i class="fa fa-phone"></i> <span>{{ config('shop.phone') }}</span></li>
                <li><i class="fa fa-envelope"></i> <span>{{ config('shop.email') }}</span></li>
                <li><i class="fa fa-map-marker"></i> <span>{{ $addressLine ?: 'Showroom ' . config('shop.name') }}</span></li>
            </ul>
            <ul class="header-utility-right">
                @if(config('shop.social_instagram'))
                    <li><a href="{{ config('shop.social_instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa fa-instagram"></i></a></li>
                @endif
                @if(config('shop.social_facebook'))
                    <li><a href="{{ config('shop.social_facebook') }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa fa-facebook"></i></a></li>
                @endif
                @if(!config('shop.social_instagram') && !config('shop.social_facebook'))
                    <li><a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- Bar 2: Main header (white) - logo left, search center, account + cart right --}}
<div class="header-main-bar">
    <div class="container">
        <button type="button" class="navbar-toggle header-mobile-toggle visible-xs" data-toggle="collapse" data-target="#header-mobile-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="header-main-inner">
            <a href="{{ route('home') }}" class="header-brand">
                <img src="{{ asset('images/LOGO.png') }}" alt="{{ config('app.name') }}" class="header-brand-img">
                {{-- <span class="header-brand-name">{{ config('app.name') }}</span> --}}
            </a>

            <form action="{{ route('search.product') }}" method="GET" class="header-search-form">
                <div class="header-search-input-group">
                    <input type="text" name="q" class="form-control header-search-input" placeholder="{{ __('Cauta un produs') }}" value="{{ request()->input('q') }}">
                    <span class="header-search-btn-wrap">
                        <button type="submit" class="header-search-btn" title="{{ __('Search') }}"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>

            <div class="header-nav-right">
                <div class="header-account-wrap header-account-dropdown">
                    @if(auth()->check())
                        <a href="{{ route('accounts', ['tab' => 'profile']) }}" class="header-account-link">
                            <i class="fa fa-user"></i>
                            <span>{{ __('Contul Meu') }}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="header-account-link header-account-dropdown-toggle">
                            <i class="fa fa-user"></i>
                            <span>{{ __('Contul Meu') }}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <div class="header-account-dropdown-menu">
                            <form action="{{ route('login') }}" method="POST" class="header-login-form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="header-login-email">{{ __('Email *') }}</label>
                                    <input type="email" id="header-login-email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="header-login-password">{{ __('Parola *') }}</label>
                                    <input type="password" id="header-login-password" name="password" class="form-control" placeholder="Parola" required>
                                </div>
                                <div class="header-login-actions">
                                    <a href="{{ route('password.request') }}" class="header-login-forgot">{{ __('Ai uitat parola?') }}</a>
                                    <button type="submit" class="btn header-login-btn">{{ __('Login') }}</button>
                                </div>
                                <a href="{{ route('register') }}" class="header-login-register">{{ __('INREGISTRARE CONT NOU') }} <i class="fa fa-arrow-right"></i></a>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="header-cart-dropdown">
                    <a href="{{ route('cart.index') }}" class="header-cart-link header-cart-dropdown-toggle" title="{{ __('Cosul meu') }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="header-cart-text">{{ __('Cosul meu') }}</span>
                        <span class="header-cart-badge">{{ $cartCount }}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <div class="header-cart-dropdown-menu">
                        @if($cartCount > 0 && isset($cartItems))
                            <div class="header-cart-preview-list">
                                @foreach($cartItems->take(5) as $item)
                                    <div class="header-cart-preview-item">
                                        @if(isset($item->cover) && $item->cover)
                                            <img src="{{ (strpos($item->cover, 'http') === 0 ? $item->cover : asset('storage/' . $item->cover)) }}" alt="{{ $item->name }}" class="header-cart-preview-img">
                                        @else
                                            <span class="header-cart-preview-noimg"></span>
                                        @endif
                                        <div class="header-cart-preview-details">
                                            <span class="header-cart-preview-name">{{ $item->name }}</span>
                                            <span class="header-cart-preview-qty">{{ $item->qty }} x {{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($cartItems->count() > 5)
                                <p class="header-cart-preview-more">{{ __('+ :count more', ['count' => $cartItems->count() - 5]) }}</p>
                            @endif
                            <div class="header-cart-preview-footer">
                                <span class="header-cart-preview-total">{{ __('Total') }}: {{ config('cart.currency_symbol') }}{{ number_format($cartSubTotal ?? 0, 2) }}</span>
                                <a href="{{ route('cart.index') }}" class="btn header-cart-preview-btn">{{ __('Vezi cos') }}</a>
                            </div>
                        @else
                            <p class="header-cart-preview-empty">{{ __('Nu sunt produse in cos.') }}</p>
                            <div class="header-cart-preview-footer header-cart-preview-footer-empty">
                                <a href="{{ route('cart.index') }}" class="btn header-cart-preview-btn">{{ __('Vezi cos') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Mobile collapse: search, account, cart, categories, utility --}}
        <div class="collapse header-mobile-collapse visible-xs" id="header-mobile-collapse">
            <div class="header-mobile-search">
                <form action="{{ route('search.product') }}" method="GET" class="header-search-form">
                    <div class="header-search-input-group">
                        <input type="text" name="q" class="form-control header-search-input" placeholder="{{ __('Cauta un produs') }}" value="{{ request()->input('q') }}">
                        <button type="submit" class="header-search-btn" title="{{ __('Search') }}"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            <ul class="header-mobile-links">
                @if(auth()->check())
                    <li><a href="{{ route('accounts', ['tab' => 'profile']) }}"><i class="fa fa-user"></i> {{ __('Contul Meu') }}</a></li>
                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                @else
                    <li><a href="{{ route('login') }}"><i class="fa fa-user"></i> {{ __('Contul Meu') }}</a></li>
                    <li><a href="{{ route('register') }}"><i class="fa fa-sign-in"></i> Register</a></li>
                @endif
                <li><a href="{{ route('cart.index') }}"><i class="fa fa-shopping-cart"></i> {{ __('Cosul meu') }} ({{ $cartCount }})</a></li>
            </ul>
            <div class="header-mobile-categories">
                @foreach($categories as $category)
                    @if($category->children()->count() > 0)
                        <div class="header-mobile-cat">
                            <strong>{{ strtoupper($category->name) }}</strong>
                            @foreach($category->children as $sub)
                                <a href="{{ route('front.category.slug', $sub->slug) }}">{{ $sub->name }}</a>
                            @endforeach
                        </div>
                    @else
                        <a href="{{ route('front.category.slug', $category->slug) }}" class="header-mobile-cat-link">{{ strtoupper($category->name) }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Bar 3: Category bar (orange-brown) - white uppercase categories --}}
<div class="header-category-bar hidden-xs">
    <div class="container">
        <nav class="header-category-nav">
            <ul class="header-category-list">
                <li>
                    <a href="{{ route('front.category.slug', 'toys') }}">TOYS</a>
                </li>
                <li>
                    <a href="{{ route('front.category.slug', 'profile-interior') }}">PROFILE INTERIOR</a>
                </li>
                <li>
                    <a href="{{ route('front.category.slug', 'profile-exterior') }}">PROFILE EXTERIOR</a>
                </li>
                <li>
                    <a href="{{ route('front.category.slug', 'tapet') }}">TAPET</a>
                </li>
                <li>
                    <a href="{{ route('front.category.slug', 'corpuri-de-iluminat-de-interior') }}">CORPURI DE ILUMINAT DE INTERIOR</a>
                </li>
                <li>
                    <a href="{{ route('front.category.slug', 'corpuri-de-iluminat-de-exterior') }}">CORPURI DE ILUMINAT DE EXTERIOR</a>
                </li>
                <!-- ...existing code for dynamic categories if needed... -->
            </ul>
        </nav>
    </div>
</div>


