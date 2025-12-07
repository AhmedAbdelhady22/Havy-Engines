<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $cartItems = $cart->items()->with('engine')->get();
        $subtotal = $cart->total;
        $tax = $subtotal * 0.08; // 8% tax (adjust as needed)
        $shipping = $subtotal > 10000 ? 0 : 500; // Free shipping over $10,000
        $total = $subtotal + $tax + $shipping;

        return view('checkout.index', compact(
            'cart',
            'cartItems',
            'subtotal',
            'tax',
            'shipping',
            'total'
        ));
    }

    /**
     * Process the checkout (without payment for now)
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'shipping_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = $cart->total;
        $tax = $subtotal * 0.08;
        $shipping = $subtotal > 10000 ? 0 : 500;
        $total = $subtotal + $tax + $shipping;

        // Use database transaction for safety
        DB::beginTransaction();

        try {
            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total' => $total,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'shipping_country' => $request->shipping_country,
                'shipping_phone' => $request->shipping_phone,
                'payment_method' => 'stripe',
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'engine_id' => $cartItem->engine_id,
                    'engine_name' => $cartItem->engine->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->subtotal,
                ]);

                // Reduce stock
                $cartItem->engine->decrement('stock_quantity', $cartItem->quantity);
            }

            // Clear the cart
            $cart->items()->delete();

            DB::commit();

            // Redirect to payment page (we'll implement this in Phase 5)
            return redirect()->route('checkout.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('checkout.index')
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Display the payment page
     */
    public function payment($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'pending')
            ->with('items')
            ->firstOrFail();

        return view('checkout.payment', compact('order'));
    }

    /**
     * Handle successful payment (placeholder - will be updated in Phase 5)
     */
    public function success($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    /**
     * Handle cancelled payment
     */
    public function cancel($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout.cancel', compact('order'));
    }

    /**
     * Display user's order history
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a single order
     */
    public function orderShow($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('items.engine')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
