@extends('layouts.staff')

@section('page-title', 'Orders Report')

@section('content')
<div class="s-page-head">
    <div>
        <div class="s-page-title">Orders Report</div>
        <div class="s-page-sub">{{ $rows->count() }} orders</div>
    </div>
    <button type="button" class="s-btn s-btn-primary" onclick="window.print()">
        <i class="ti ti-file-type-pdf"></i> Download PDF
    </button>
</div>

@php
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];
    $statuses = ['pending', 'confirmed', 'picking', 'packed', 'delivered', 'cancelled'];

    $activeCustomer = request()->filled('user_id') ? $customers->firstWhere('id', request('user_id')) : null;
@endphp

<div class="s-card">
    <div class="s-card-body">
        <form method="GET" action="{{ route('staff.reports.orders') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">

            <div class="s-form-group" style="margin-bottom:0;">
                <label class="s-label">From date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="s-input">
            </div>

            <div class="s-form-group" style="margin-bottom:0;">
                <label class="s-label">To date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="s-input">
            </div>

            <div class="s-form-group" style="margin-bottom:0;">
                <label class="s-label">Month</label>
                <select name="month" class="s-input">
                    <option value="">All months</option>
                    @foreach($months as $num => $label)
                        <option value="{{ $num }}" {{ (int) request('month') === $num ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="s-form-group" style="margin-bottom:0;">
                <label class="s-label">Year</label>
                <select name="year" class="s-input">
                    <option value="">All years</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ (string) request('year') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="s-form-group" style="margin-bottom:0;">
                <label class="s-label">Customer</label>
                <select name="user_id" class="s-input">
                    <option value="">All customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ (string) request('user_id') === (string) $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="s-form-group" style="margin-bottom:0;">
                <label class="s-label">Status</label>
                <select name="status" class="s-input">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="s-btn s-btn-primary"><i class="ti ti-filter"></i> Filter</button>

            @if(request()->hasAny(['date_from', 'date_to', 'month', 'year', 'user_id', 'status']))
                <a href="{{ route('staff.reports.orders') }}" class="s-btn s-btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div class="print-area">
        <div class="s-print-header">
            <h1>Farm Direct — Orders Report</h1>
            <div class="s-print-meta">
                Generated {{ now()->format('d M Y, h:i A') }}
                @if(request()->filled('date_from')) &middot; From: {{ request('date_from') }} @endif
                @if(request()->filled('date_to')) &middot; To: {{ request('date_to') }} @endif
                @if(request()->filled('month')) &middot; Month: {{ $months[(int) request('month')] ?? '' }} @endif
                @if(request()->filled('year')) &middot; Year: {{ request('year') }} @endif
                @if($activeCustomer) &middot; Customer: {{ $activeCustomer->name }} @endif
                @if(request()->filled('status')) &middot; Status: {{ ucfirst(request('status')) }} @endif
                &middot; {{ $rows->count() }} orders
            </div>
        </div>

        <div style="overflow-x:auto;">
        <table class="s-table">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <x-a-sortable-th column="status" label="Status" :sort="$sort" :direction="$direction" />
                <x-a-sortable-th column="total" label="Total" :sort="$sort" :direction="$direction" />
                <x-a-sortable-th column="created_at" label="Date" :sort="$sort" :direction="$direction" />
                <th>Updated By</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>#{{ $row->id }}</td>
                    <td>{{ $row->user->name ?? '—' }}</td>
                    <td><span class="s-badge s-badge-{{ $row->status }}">{{ ucfirst($row->status) }}</span></td>
                    <td>₹{{ number_format($row->total, 2) }}</td>
                    <td>{{ $row->created_at->format('d M Y') }}</td>
                    <td>{{ $row->updatedByStaff->name ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="s-empty">No orders found for this filter.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

<style>
    .s-print-header {
        display: none;
    }

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

        .print-area div[style*="overflow-x"] {
            overflow: visible !important;
        }

        .s-print-header {
            display: block;
            margin-bottom: 16px;
            border-bottom: 2px solid #4a4a3a;
            padding-bottom: 10px;
        }

        .s-print-header h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }

        .s-print-meta {
            font-size: 11px;
            color: #666;
        }

        .s-table {
            width: 100%;
            table-layout: auto;
        }

        .s-table tr {
            page-break-inside: avoid;
        }

        .s-table th {
            background: #f2f0ea !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection