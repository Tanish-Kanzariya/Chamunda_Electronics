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
    /**
     * Handle instant "Buy Now" for a single particular product.
     */
    public function buyNow(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);

        if (!$product->in_stock) {
            return back()->with('error', 'Sorry, ' . $product->name . ' is currently out of stock.');
        }

        if ($quantity > $product->stock_quantity) {
            return back()->with('error', 'Only ' . $product->stock_quantity . ' units available in stock.');
        }

        session()->put('buy_now', [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => (float) $product->effective_price,
            'quantity' => $quantity,
            'image' => $product->image,
            'category' => $product->category->name ?? 'Electrical',
        ]);

        return redirect()->route('checkout.index', ['direct' => 1]);
    }

    /**
     * GET shortcut for Buy Now from links/buttons.
     */
    public function buyNowGet(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = max(1, (int) $request->input('quantity', 1));

        if (!$product->in_stock) {
            return back()->with('error', 'Sorry, ' . $product->name . ' is currently out of stock.');
        }

        session()->put('buy_now', [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => (float) $product->effective_price,
            'quantity' => $quantity,
            'image' => $product->image,
            'category' => $product->category->name ?? 'Electrical',
        ]);

        return redirect()->route('checkout.index', ['direct' => 1]);
    }

    /**
     * Cancel single product Buy Now mode and revert to normal cart.
     */
    public function cancelBuyNow()
    {
        session()->forget('buy_now');
        return redirect()->route('checkout.index')->with('info', 'Switched back to your shopping cart checkout.');
    }

    /**
     * Display the checkout screen for either full cart OR single particular product.
     */
    public function index(Request $request)
    {
        // Support direct product_id query
        if ($request->filled('product_id')) {
            $product = Product::find($request->input('product_id'));
            if ($product && $product->in_stock) {
                $qty = max(1, (int) $request->input('quantity', 1));
                session()->put('buy_now', [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'price' => (float) $product->effective_price,
                    'quantity' => $qty,
                    'image' => $product->image,
                    'category' => $product->category->name ?? 'Electrical',
                ]);
            }
        }

        $buyNow = session()->get('buy_now');
        $cart = session()->get('cart', []);

        if ($buyNow) {
            $items = [$buyNow['id'] => $buyNow];
            $isBuyNow = true;
            $buyNowProduct = $buyNow;
        } else {
            $items = $cart;
            $isBuyNow = false;
            $buyNowProduct = null;

            if (empty($items)) {
                return redirect()->route('shop.index')->with('error', 'Your shopping cart is empty. Add products to proceed to checkout.');
            }
        }

        $coupon = session()->get('coupon');

        // Subtotal calculation
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Coupon calculation
        $discount = 0;
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
            }
        }

        // Shipping calculation: Free over ₹999, else ₹99
        $shipping = ($subtotal - $discount) > 999 ? 0 : 99;
        
        // Detailed 18% GST calculation (Standard for electrical goods in India)
        $tax = round(($subtotal - $discount) * 0.18, 2);
        $cgst = round($tax / 2, 2);
        $sgst = round($tax / 2, 2);
        $total = max(0, $subtotal - $discount + $shipping + $tax);

        $user = Auth::user();

        return view('checkout.index', compact(
            'items',
            'isBuyNow',
            'buyNowProduct',
            'cart',
            'coupon',
            'subtotal',
            'discount',
            'shipping',
            'tax',
            'cgst',
            'sgst',
            'total',
            'user'
        ));
    }

    /**
     * Process and place the order with real-world e-commerce validations.
     */
    public function process(Request $request)
    {
        $isBuyNow = $request->boolean('is_buy_now') || session()->has('buy_now');
        $buyNow = session()->get('buy_now');
        $cart = session()->get('cart', []);

        if ($isBuyNow && $buyNow) {
            $items = [$buyNow['id'] => $buyNow];
        } else {
            $items = $cart;
            if (empty($items)) {
                return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
            }
        }

        // Real-world Indian E-Commerce form validations
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'customer_phone' => ['required', 'string', 'regex:/^[0-9\+\-\s]{10,15}$/'],
            'alternate_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:150',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
            'address_type' => 'nullable|in:home,commercial_site,office_warehouse',
            'delivery_type' => 'nullable|in:standard,express',
            'has_gst' => 'nullable',
            'company_name' => 'nullable|string|max:150',
            'gstin' => ['nullable', 'string', 'max:20'],
            'payment_method' => 'required|in:cod,online_upi,card,netbanking',
            'upi_id' => 'nullable|string|max:100',
            'card_number' => 'nullable|string|max:30',
            'card_holder' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'save_profile_address' => 'nullable',
        ], [
            'pincode.regex' => 'Please provide a valid 6-digit Indian PIN code (e.g. 380001).',
            'customer_phone.regex' => 'Please enter a valid 10-digit mobile phone number.',
        ]);

        // Stock availability verification before locking order
        foreach ($items as $item) {
            $p = Product::find($item['id']);
            if (!$p || $p->stock_quantity < $item['quantity']) {
                $avail = $p ? $p->stock_quantity : 0;
                return back()->with('error', "Insufficient stock for {$item['name']}. Only {$avail} units left in stock.");
            }
        }

        // Calculations
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $coupon = session()->get('coupon');
        $discount = 0;
        $couponCode = null;
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
                $couponCode = $couponModel->code;
            }
        }

        // Delivery charge (Express priority dispatch = ₹149; Standard = Free over ₹999 else ₹99)
        $deliveryType = $validated['delivery_type'] ?? 'standard';
        if ($deliveryType === 'express') {
            $shipping = 149;
        } else {
            $shipping = ($subtotal - $discount) > 999 ? 0 : 99;
        }

        $tax = round(($subtotal - $discount) * 0.18, 2);
        $total = max(0, $subtotal - $discount + $shipping + $tax);

        // Build comprehensive real-world order notes metadata
        $metaNotes = [];
        if (!empty($validated['landmark'])) {
            $metaNotes[] = "Landmark: " . $validated['landmark'];
        }
        if (!empty($validated['address_type'])) {
            $typeLabels = [
                'home' => 'Residential (Home)',
                'commercial_site' => 'Commercial / Contractor Site',
                'office_warehouse' => 'Office / Warehouse',
            ];
            $metaNotes[] = "Address Type: " . ($typeLabels[$validated['address_type']] ?? $validated['address_type']);
        }
        if ($request->has('has_gst') && !empty($validated['gstin'])) {
            $metaNotes[] = "Business GST Invoice: GSTIN {$validated['gstin']} (" . ($validated['company_name'] ?? 'Contractor') . ")";
        }
        if ($deliveryType === 'express') {
            $metaNotes[] = "Dispatch: Priority Express (Same-Day / Next-Day Dispatch)";
        }
        if ($validated['payment_method'] === 'online_upi' && !empty($validated['upi_id'])) {
            $metaNotes[] = "UPI ID: {$validated['upi_id']}";
        }
        if (!empty($validated['notes'])) {
            $metaNotes[] = "Customer Note: " . $validated['notes'];
        }

        $finalNotesString = implode(" | ", $metaNotes);

        try {
            $order = DB::transaction(function () use ($validated, $items, $subtotal, $discount, $couponCode, $tax, $shipping, $total, $finalNotesString) {
                // Generate clean, realistic order number: CE-YYYYMMDD-XXXXX
                $orderNumber = 'CE-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                $paymentStatus = in_array($validated['payment_method'], ['online_upi', 'card', 'netbanking']) ? 'paid' : 'pending';

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
                    'payment_status' => $paymentStatus,
                    'order_status' => 'pending',
                    'notes' => $finalNotesString,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'product_sku' => $item['sku'] ?? null,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Decrement inventory atomically
                    $product = Product::find($item['id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                // If user opted to save address to profile
                if (Auth::check() && request()->has('save_profile_address')) {
                    $u = Auth::user();
                    $u->address = $validated['shipping_address'];
                    $u->city = $validated['city'];
                    $u->state = $validated['state'];
                    $u->pincode = $validated['pincode'];
                    $u->phone = $validated['customer_phone'];
                    $u->save();
                }

                return $order;
            });

            // Cleanup session: if buy_now, only forget buy_now; keep cart safe!
            if ($isBuyNow) {
                session()->forget('buy_now');
            } else {
                session()->forget('cart');
            }
            session()->forget('coupon');

            return redirect()->route('checkout.success', $order->order_number)->with('success', 'Order placed successfully! Check your email for invoice copy.');
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
