<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function orders(Request $request)
    {
        $statuses = ['pending', 'confirmed', 'picking', 'packed', 'delivered', 'cancelled'];

        $query = Order::with('items.product')
            ->where('user_id', auth()->id());

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->latest()->get();

        // Sequence numbers based on this user's FULL order history (unaffected by filters),
        // so "Order #1" always means their first order ever placed, regardless of what's
        // currently being viewed/filtered.
        $sequenceMap = Order::where('user_id', auth()->id())
            ->orderBy('created_at')
            ->pluck('id')
            ->flip()
            ->map(fn ($index) => $index + 1);

        return view('shop.reports.orders', compact('rows', 'statuses', 'sequenceMap'));
    }
}