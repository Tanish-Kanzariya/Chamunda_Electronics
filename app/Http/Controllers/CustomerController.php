<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return view('customer.dashboard', compact('user', 'recentOrders', 'totalOrders', 'wishlistCount'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('customer.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->state = $validated['state'];
        $user->pincode = $validated['pincode'];
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.category', 'product.brand')
            ->latest()
            ->get();

        return view('customer.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(Request $request)
    {
        $productId = $request->input('product_id');
        $user = Auth::user();

        $exists = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            $action = 'removed';
            $message = 'Product removed from your wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $action = 'added';
            $message = 'Product saved to your wishlist!';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'count' => Wishlist::where('user_id', $user->id)->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
