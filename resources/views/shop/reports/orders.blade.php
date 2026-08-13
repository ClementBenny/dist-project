@extends('layouts.public')

@section('title', 'My Orders Report — Farm Direct')

@section('content')
<div class="page-wrap">
    <a href="{{ route('shop.orders') }}" class="back-link">
        <i class="ph ph-arrow-left"></i> Back to Orders
    </a>

    <h1 class="page-heading">My Order Report</h1>
    <p class="page-sub">{{ $rows->count() }} orders</p>

    <div style="display:flex; justify-content:flex-end; margin-bottom:24px;" class="no-print">
        <button type="button" class="btn-primary" onclick="window.print()">
            <i class="ph ph-file-pdf"></i> Download PDF
        </button>
    </div>

    <div class="fd-card no-print" style="padding:28px 32px;">
        <form method="GET" action="{{ route('shop.reports.orders') }}" style="display:flex; gap:20px; align-items:flex-end; flex-wrap:wrap;">
            <div>
                <label style="display:block; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:var(--olive); margin-bottom:6px;">From date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    style="padding:9px 12px; border:1.5px solid rgba(75,54,33,0.25); border-radius:8px; background:var(--ivory); font-family:'Jost',sans-serif; font-size:13px;">
            </div>

            <div>
                <label style="display:block; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:var(--olive); margin-bottom:6px;">To date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    style="padding:9px 12px; border:1.5px solid rgba(75,54,33,0.25); border-radius:8px; background:var(--ivory); font-family:'Jost',sans-serif; font-size:13px;">
            </div>

            <div>
                <label style="display:block; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:var(--olive); margin-bottom:6px;">Status</label>
                <select name="status" style="padding:9px 12px; border:1.5px solid rgba(75,54,33,0.25); border-radius:8px; background:var(--ivory); font-family:'Jost',sans-serif; font-size:13px;">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary">Filter</button>

            @if(request()->hasAny(['date_from', 'date_to', 'status']))
                <a href="{{ route('shop.reports.orders') }}" class="btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div class="print-area">
        <div class="print-header">
            <h2 style="font-family:'Cormorant Garamond', serif; font-size:24px; color:var(--umber); margin-bottom:4px;">
                Farm Direct — My Order Report
            </h2>
            <div style="font-size:12px; color:var(--mauve);">
                Generated {{ now()->format('d M Y, h:i A') }}
                @if(request()->filled('date_from')) &middot; From: {{ request('date_from') }} @endif
                @if(request()->filled('date_to')) &middot; To: {{ request('date_to') }} @endif
                @if(request()->filled('status')) &middot; Status: {{ ucfirst(request('status')) }} @endif
                &middot; {{ $rows->count() }} orders
            </div>
        </div>

        @if($rows->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="ph ph-receipt"></i></div>
                <h3>No orders found</h3>
                <p>Try adjusting your filters, or place your first order.</p>
            </div>
        @else
            <div class="fd-card fd-card--flush">
                <table class="fd-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Order</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th class="no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                <td>Order #{{ str_pad($sequenceMap[$row->id], 3, '0', STR_PAD_LEFT) }}</td>
                                <td><span class="status-badge status-{{ $row->status }}">{{ ucfirst($row->status) }}</span></td>
                                <td class="muted">{{ $row->items->count() }}</td>
                                <td>₹{{ number_format($row->total, 2) }}</td>
                                <td class="muted">{{ $row->created_at->format('d M Y') }}</td>
                                <td class="no-print">
                                    <a href="{{ route('shop.orders.show', $row) }}" style="color:var(--mauve); text-decoration:none;">
                                        <i class="ph ph-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    .print-header { display: none; }

    @media print {
        body * {
            visibility: hidden;
        }

        .print-area,
        .print-area * {
            visibility: visible;
        }

        .print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
        }

        .print-header {
            display: block;
            margin-bottom: 16px;
            border-bottom: 2px solid var(--umber);
            padding-bottom: 10px;
        }

        .fd-table th, .fd-table td {
            color: #000 !important;
        }
    }
</style>
@endsection