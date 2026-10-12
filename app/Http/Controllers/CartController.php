<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
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
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }

        $shipping = ($subtotal - $discount) > 999 || $subtotal == 0 ? 0 : 99;
        $tax = round(($subtotal - $discount) * 0.18, 2); // 18% GST standard electrical
        $total = max(0, $subtotal - $discount + $shipping + $tax);

        return view('cart.index', compact('cart', 'coupon', 'subtotal', 'discount', 'shipping', 'tax', 'total'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = max(1, (int) $request->input('quantity', 1));

        $product = Product::findOrFail($productId);

        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Sorry, insufficient stock available for ' . $product->name);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;
            if ($product->stock_quantity < $newQty) {
                return back()->with('error', 'Cannot add more. Reached maximum available stock.');
            }
            $cart[$productId]['quantity'] = $newQty;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->effective_price,
                'quantity' => $quantity,
                'image' => $product->image,
                'category' => $product->category->name ?? 'Electrical',
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Added to cart!',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', $product->name . ' added to your shopping cart!');
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $product = Product::find($productId);
                if ($product && $product->stock_quantity < $quantity) {
                    return back()->with('error', 'Only ' . $product->stock_quantity . ' units available in stock.');
                }
                $cart[$productId]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');
        return back()->with('success', 'Shopping cart cleared.');
    }

    public function applyCoupon(Request $request)
    {
        $code = trim(strtoupper($request->input('coupon_code')));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if (!$coupon->isValidForAmount($subtotal)) {
            return back()->with('error', 'Coupon requires minimum order of ₹' . number_format($coupon->min_order_amount, 2));
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'max_discount' => (float) $coupon->max_discount,
        ]);

        return back()->with('success', 'Coupon ' . $code . ' applied successfully!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }
}
