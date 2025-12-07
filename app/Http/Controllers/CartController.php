<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get or create a cart for the current user/session
     */
    private function getCart()
    {
        if (Auth::check()) {
            // Logged-in user: get or create cart by user_id
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );
        } else {
            // Guest user: get or create cart by session_id
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => null]
            );
        }

        return $cart;
    }

    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('engine')->get();
        $total = $cart->total;

        return view('cart.index', compact('cart', 'cartItems', 'total'));
    }

    /**
     * Add an item to the cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'engine_id' => 'required|exists:engines,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $engine = Engine::findOrFail($request->engine_id);

        // Check stock
        if ($engine->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available. Only ' . $engine->stock_quantity . ' left.',
            ], 400);
        }

        $cart = $this->getCart();

        // Check if item already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('engine_id', $engine->id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($engine->stock_quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more. Only ' . $engine->stock_quantity . ' available.',
                ], 400);
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'engine_id' => $engine->id,
                'quantity' => $request->quantity,
                'price' => $engine->current_price, // Use sale price if available
            ]);
        }

        // Reload cart for updated totals
        $cart->load('items');

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart!',
            'cartCount' => $cart->item_count,
            'cartTotal' => number_format($cart->total, 2),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = $this->getCart();
        $cartItem = CartItem::where('id', $itemId)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        // Check stock
        if ($cartItem->engine->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock. Only ' . $cartItem->engine->stock_quantity . ' available.',
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        // Reload cart for updated totals
        $cart->load('items');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated!',
            'itemSubtotal' => number_format($cartItem->subtotal, 2),
            'cartCount' => $cart->item_count,
            'cartTotal' => number_format($cart->total, 2),
        ]);
    }

    /**
     * Remove an item from the cart
     */
    public function remove($itemId)
    {
        $cart = $this->getCart();
        $cartItem = CartItem::where('id', $itemId)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        $cartItem->delete();

        // Reload cart for updated totals
        $cart->load('items');

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cartCount' => $cart->item_count,
            'cartTotal' => number_format($cart->total, 2),
        ]);
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared!',
            'cartCount' => 0,
            'cartTotal' => '0.00',
        ]);
    }

    /**
     * Get cart count (for AJAX updates)
     */
    public function count()
    {
        $cart = $this->getCart();

        return response()->json([
            'count' => $cart->item_count,
            'total' => number_format($cart->total, 2),
        ]);
    }

    /**
     * Merge guest cart with user cart after login
     */
    public function mergeGuestCart()
    {
        if (!Auth::check()) {
            return;
        }

        $sessionId = session()->getId();
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['session_id' => null]
        );

        // Move items from guest cart to user cart
        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()
                ->where('engine_id', $guestItem->engine_id)
                ->first();

            if ($existingItem) {
                // Update quantity if item already exists
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity,
                ]);
            } else {
                // Move item to user cart
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        // Delete the guest cart
        $guestCart->delete();
    }
}
