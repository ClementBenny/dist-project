<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function orders(Request $request)
    {
        $sort = $this->resolveSort($request->get('sort', 'created_at'), ['status', 'total', 'created_at']);
        $direction = $request->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = Order::with(['user', 'updatedByStaff']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->orderBy($sort, $direction)->get();

        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $years = Order::selectRaw('DISTINCT strftime("%Y", created_at) as year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('staff.reports.orders', compact('rows', 'sort', 'direction', 'customers', 'years'));
    }

    private function resolveSort(string $column, array $allowed): string
    {
        return in_array($column, $allowed, true) ? $column : $allowed[0];
    }
}