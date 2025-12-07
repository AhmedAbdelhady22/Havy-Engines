<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share cart data with all views
        View::composer('*', function ($view) {
            $cartCount = 0;
            $cartTotal = 0;

            try {
                if (Auth::check()) {
                    $cart = Cart::where('user_id', Auth::id())->first();
                } else {
                    $sessionId = session()->getId();
                    $cart = Cart::where('session_id', $sessionId)->first();
                }

                if ($cart) {
                    $cart->load('items');
                    $cartCount = $cart->item_count;
                    $cartTotal = $cart->total;
                }
            } catch (\Exception $e) {
                // Silently fail if database not ready
            }

            $view->with('cartCount', $cartCount);
            $view->with('cartTotal', $cartTotal);
        });
    }
}
