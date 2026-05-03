@extends('layouts.public')

@section('title', 'My Orders — Farm Direct Wholesale')

@section('content')

@php
    $stepLabels = [
        'pending'   => 'Order Placed',
        'confirmed' => 'Confirmed',
        'picking'   => 'Being Picked',
        'packed'    => 'Packed',
        'delivered' => 'Delivered',
    ];

    $stepIcons = [
        'pending'   => 'ph-receipt',
        'confirmed' => 'ph-seal-check',
        'picking'   => 'ph-basket',
        'packed'    => 'ph-package',
        'delivered' => 'ph-truck',
    ];
@endphp

<style>
    .orders-wrap {
        min-height: 100vh;
        padding: 120px 48px 80px;
        background: var(--ivory);
        max-width: 900px;
        margin: 0 auto;
    }

    .orders-heading {
        font-family: 'Cormorant Garamond', serif;
        font-size: 56px; font-weight: 600;
        color: var(--umber); line-height: 1.1;
        margin-bottom: 6px;
    }
    .orders-sub {
        font-size: 16px; color: var(--umber);
        margin-bottom: 48px; letter-spacing: 0.02em; opacity: 0.6;
    }

    .order-card {
        background: var(--champagne);
        border: 1.5px solid rgba(75,54,33,0.18);
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 24px;
        transition: box-shadow 0.25s, transform 0.25s;
    }
    .order-card:hover {
        box-shadow: 0 10px 40px rgba(75,54,33,0.13);
        transform: translateY(-2px);
    }

    .order-card-top {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
    }

    .order-ref {
        font-family: 'Cormorant Garamond', serif;
        font-size: 28px; font-weight: 600;
        color: var(--umber); letter-spacing: 0.02em;
    }
    .order-meta {
        font-size: 15px; color: var(--umber);
        margin-top: 5px; letter-spacing: 0.02em; opacity: 0.65;
    }

    .order-card-actions { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }

    .status-badge {
        font-size: 11px; font-weight: 600; letter-spacing: 0.1em;
        text-transform: uppercase; padding: 6px 16px;
        border-radius: 999px; white-space: nowrap;
    }
    .status-pending   { background: rgba(75,54,33,0.12);  color: var(--umber); border: 1.5px solid rgba(75,54,33,0.3); }
    .status-confirmed { background: rgba(128,128,0,0.15); color: #4a5a00;      border: 1.5px solid rgba(128,128,0,0.4); }
    .status-picking   { background: rgba(75,54,33,0.18);  color: var(--umber); border: 1.5px solid rgba(75,54,33,0.4); }
    .status-packed    { background: rgba(128,128,0,0.2);  color: #3d4d00;      border: 1.5px solid rgba(128,128,0,0.5); }
    .status-delivered { background: var(--olive);         color: #fff;         border: 1.5px solid var(--olive); }
    .status-cancelled { background: rgba(140,40,40,0.12); color: #8c2828;      border: 1.5px solid rgba(140,40,40,0.3); }

    .order-view-link {
        font-size: 14px; font-weight: 500; letter-spacing: 0.06em;
        color: var(--umber); text-decoration: none;
        border-bottom: 1.5px solid var(--umber); padding-bottom: 1px;
        transition: color 0.2s, border-color 0.2s; white-space: nowrap;
    }
    .order-view-link:hover { color: var(--olive); border-color: var(--olive); }

    .card-divider { height: 1px; background: rgba(75,54,33,0.15); margin-bottom: 28px; }

    /* ── PROGRESS ── */
    .progress-wrap {
        position: relative;
        display: flex; align-items: flex-start;
        justify-content: space-between;
    }
    .progress-track {
        position: absolute;
        top: 22px;
        left: calc(10% + 22px);
        right: calc(10% + 22px);
        height: 2px;
        background: rgba(75,54,33,0.18);
        z-index: 0;
    }
    .progress-fill {
        position: absolute; top: 0; left: 0;
        height: 100%; background: var(--olive);
        transition: width 0.5s ease;
    }
    .progress-step {
        position: relative; z-index: 2;
        display: flex; flex-direction: column;
        align-items: center; text-align: center;
        width: 20%; gap: 10px;
    }
    .step-dot {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: all 0.3s;
        position: relative; z-index: 2;
    }
    .step-dot i { font-size: 20px; line-height: 1; }

    .step-dot--done    { background: var(--olive); box-shadow: 0 4px 12px rgba(128,128,0,0.3); }
    .step-dot--done i  { color: #fff; font-size: 22px; }

    .step-dot--current { background: var(--umber); box-shadow: 0 0 0 7px rgba(75,54,33,0.12), 0 4px 14px rgba(75,54,33,0.25); }
    .step-dot--current i { color: var(--champagne); font-size: 20px; }

    .step-dot--future  { background: var(--ivory); border: 2px solid rgba(75,54,33,0.2); }
    .step-dot--future i { color: rgba(75,54,33,0.3); font-size: 20px; }

    .step-dot--done .ph-fill.ph-check-circle { display: block; }
    .step-dot--done .step-ph                  { display: none;  }
    .step-dot:not(.step-dot--done) .ph-fill.ph-check-circle { display: none;  }
    .step-dot:not(.step-dot--done) .step-ph                  { display: block; }

    .step-label { font-size: 12px; letter-spacing: 0.04em; line-height: 1.35; font-weight: 400; }
    .step-label--done    { color: var(--olive); font-weight: 500; }
    .step-label--current { color: var(--umber); font-weight: 700; }
    .step-label--future  { color: rgba(75,54,33,0.38); }

    .cancelled-notice {
        display: flex; align-items: center; gap: 12px;
        padding: 16px 20px;
        background: rgba(140,40,40,0.08);
        border: 1.5px solid rgba(140,40,40,0.22);
        border-radius: 12px;
        font-size: 15px; color: #8c2828;
    }
    .cancelled-notice i { font-size: 20px; flex-shrink: 0; }

    .empty-state { text-align: center; padding: 100px 24px; }
    .empty-state-icon {
        width: 72px; height: 72px; border-radius: 50%;
        background: rgba(75,54,33,0.08);
        border: 1.5px solid rgba(75,54,33,0.15);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
    }
    .empty-state-icon i { font-size: 30px; color: rgba(75,54,33,0.35); }
    .empty-state h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 34px; font-weight: 600;
        color: var(--umber); margin-bottom: 10px;
    }
    .empty-state p { font-size: 16px; color: var(--umber); opacity: 0.55; margin-bottom: 32px; }
    .btn-shop {
        display: inline-block;
        background: var(--umber); color: var(--ivory);
        font-size: 12px; letter-spacing: 0.12em; text-transform: uppercase;
        padding: 14px 32px; border-radius: 999px; text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-shop:hover { background: var(--olive); transform: translateY(-1px); }

    @media (max-width: 640px) {
        .orders-wrap    { padding: 100px 20px 60px; }
        .order-card     { padding: 24px 20px; }
        .orders-heading { font-size: 40px; }
        .step-label     { font-size: 10px; }
        .step-dot       { width: 34px; height: 34px; }
        .step-dot i     { font-size: 15px !important; }
        .progress-track { top: 17px; left: calc(10% + 17px); right: calc(10% + 17px); }
    }
</style>

<div class="orders-wrap">

    <p class="section-label">Wholesale Account</p>
    <h1 class="orders-heading">Your Orders</h1>
    <p class="orders-sub">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} placed</p>

    @forelse($orders as $order)
        @php
            $steps = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
            $progressPercent = ($currentIndex !== false && count($steps) > 1)
                ? ($currentIndex / (count($steps) - 1)) * 100
                : 0;
        @endphp

        <div class="order-card">
            <div class="order-card-top">
                <div>
                    <p class="order-ref">#{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}</p>
                    <p class="order-meta">
                        {{ $order->created_at->format('d M Y') }} &nbsp;·&nbsp; ₹{{ number_format($order->total, 2) }}
                    </p>
                </div>
                <div class="order-card-actions">
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    <a href="{{ route('wholesale.orders.show', $order) }}" class="order-view-link">View details →</a>
                </div>
            </div>

            <div class="card-divider"></div>

            @if($order->status === 'cancelled')
                <div class="cancelled-notice">
                    <i class="ph ph-x-circle"></i>
                    <span>This order was cancelled and will not be processed.</span>
                </div>
            @else
                <div class="progress-wrap">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    @foreach($steps as $i => $step)
                        @php
                            $isDone    = $currentIndex !== false && $i < $currentIndex;
                            $isCurrent = $currentIndex !== false && $i === $currentIndex;
                            $dotClass   = $isDone    ? 'step-dot--done'    : ($isCurrent ? 'step-dot--current' : 'step-dot--future');
                            $labelClass = $isDone    ? 'step-label--done'  : ($isCurrent ? 'step-label--current' : 'step-label--future');
                        @endphp
                        <div class="progress-step">
                            <div class="step-dot {{ $dotClass }}">
                                <i class="ph-fill ph-check-circle"></i>
                                <i class="ph-light {{ $stepIcons[$step] }} step-ph"></i>
                            </div>
                            <p class="step-label {{ $labelClass }}">{{ $stepLabels[$step] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon"><i class="ph-light ph-receipt"></i></div>
            <h3>No orders yet</h3>
            <p>When you place an order, it will appear here.</p>
            <a href="{{ route('wholesale.index') }}" class="btn-shop">Browse wholesale</a>
        </div>
    @endforelse

</div>

@endsection