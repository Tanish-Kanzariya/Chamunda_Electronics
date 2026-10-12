<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty. Add products to proceed to checkout.');
        }

        $coupon = session()->get('coupon');

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
            }
        }

        $shipping = ($subtotal - $discount) > 999 ? 0 : 99;
        $tax = round(($subtotal - $discount) * 0.18, 2);
        $total = max(0, $subtotal - $discount + $shipping + $tax);

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'coupon', 'subtotal', 'discount', 'shipping', 'tax', 'total', 'user'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'payment_method' => 'required|in:cod,online_upi,card',
            'notes' => 'nullable|string|max:500',
        ]);

        $coupon = session()->get('coupon');

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        $couponCode = null;
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
                $couponCode = $couponModel->code;
            }
        }

        $shipping = ($subtotal - $discount) > 999 ? 0 : 99;
        $tax = round(($subtotal - $discount) * 0.18, 2);
        $total = max(0, $subtotal - $discount + $shipping + $tax);

        try {
            $order = DB::transaction(function () use ($validated, $cart, $subtotal, $discount, $couponCode, $tax, $shipping, $total) {
                $orderNumber = 'CE-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'pincode' => $validated['pincode'],
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'coupon_code' => $couponCode,
                    'tax_amount' => $tax,
                    'shipping_charge' => $shipping,
                    'total_amount' => $total,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'paid',
                    'order_status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($cart as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'product_sku' => $item['sku'] ?? null,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Decrement product inventory
                    $product = Product::find($item['id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                return $order;
            });

            // Clear session cart and coupon
            session()->forget('cart');
            session()->forget('coupon');

            return redirect()->route('checkout.success', $order->order_number)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to place order: ' . $e->getMessage())->withInput();
        }
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items.product')->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    public function invoice($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('checkout.invoice', compact('order'));
    }
}
