<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function messages()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.customers.messages', compact('messages'));
    }

    public function markMessageRead($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }
}
