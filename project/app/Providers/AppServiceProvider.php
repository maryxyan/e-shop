<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Shop\Categories\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Cashier::useCurrency(config('cart.currency'), config('cart.currency_symbol'));
        View::composer([
            'layouts.front.header-cart',
            'layouts.front.category-nav',
        ], function ($view) {
            $view->with('categories', Category::where('parent_id', null)->orderBy('name')->get());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
