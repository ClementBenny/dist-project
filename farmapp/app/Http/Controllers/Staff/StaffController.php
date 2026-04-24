<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Orders that need action today — not delivered or cancelled
        $activeOrders = Order::with('user')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderBy('created_at')
            ->get();

        $statusCounts = [
            'pending'   => $activeOrders->where('status', 'pending')->count(),
            'confirmed' => $activeOrders->where('status', 'confirmed')->count(),
            'picking'   => $activeOrders->where('status', 'picking')->count(),
            'packed'    => $activeOrders->where('status', 'packed')->count(),
        ];

        return view('staff.dashboard', compact('activeOrders', 'statusCounts'));
    }

    public function orders()
    {
        $orders = Order::with('user')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderByRaw("CASE status
                WHEN 'picking'   THEN 1
                WHEN 'confirmed' THEN 2
                WHEN 'pending'   THEN 3
                WHEN 'packed'    THEN 4
                ELSE 5 END")
            ->get();

        return view('staff.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('staff.orders-show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                'confirmed', 'picking', 'packed', 'delivered', 'cancelled'
            ])],
        ]);

        $order->update($validated);

        return redirect()->route('staff.orders.show', $order)
            ->with('success', 'Order marked as ' . ucfirst($validated['status']) . '.');
    }
}