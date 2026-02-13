<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    {{-- Category navbar --}}
    <ul class="nav navbar-nav navbar-categories" style="width:100%;justify-content:center;">
        <li style="float:none;display:inline-block;">
            <a href="#" style="font-size:24px;padding:20px 40px;text-transform:uppercase;">Toys</a>
        </li>
    </ul>
    {{-- End category navbar --}}
    <ul class="nav navbar-nav navbar-right top-bar-links">
        @if(auth()->check())
            <li><a href="{{ route('accounts', ['tab' => 'profile']) }}"><i class="fa fa-home"></i> My Account</a></li>
            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
        @else
            <li><a href="{{ route('login') }}"><i class="fa fa-lock"></i> Login</a></li>
            <li><a href="{{ route('register') }}"><i class="fa fa-sign-in"></i> Register</a></li>
        @endif
        <li id="cart" class="menubar-cart">
            <a href="{{ route('cart.index') }}" title="View Cart" class="awemenu-icon menu-shopping-cart">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span class="cart-number">{{ $cartCount }}</span>
            </a>
        </li>
        <li class="navbar-search-li">
            <!-- search form -->
            <form action="{{ route('search.product') }}" method="GET" class="form-inline navbar-search-form" style="margin: 0;">
                <div class="input-group navbar-search-input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..." value="{{ request()->input('q') }}">
                    <span class="input-group-btn">
                        <button type="submit" id="search-btn" class="btn btn-flat navbar-search-btn" title="Search"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->
        </li>
    </ul>
</div><!-- /.navbar-collapse -->
