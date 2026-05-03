@extends('layouts.public')

@section('title', 'Order Detail — Farm Direct')

@section('content')

@php
    $steps = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
    $currentIndex = array_search($order->status, $steps);
    $cancelled = $order->status === 'cancelled';
    $progressPercent = ($currentIndex !== false && count($steps) > 1)
        ? ($currentIndex / (count($steps) - 1)) * 100
        : 0;

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

    // Contrasting icon colors per state
    // done = white (on olive bg), current = white (on umber bg), future = warm mauve
@endphp

<style>
    :root {
        --ivory:    #FFFBF0;
        --champagne:#F7E7CE;
        --mauve:    #C4A484;
        --olive:    #808000;
        --umber:    #4B3621;
    }

    .detail-wrap {
        min-height: 100vh;
        padding: 120px 48px 80px;
        max-width: 900px;
        margin: 0 auto;
        background: var(--ivory);
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: 14px; letter-spacing: 0.05em;
        color: var(--mauve); text-decoration: none;
        margin-bottom: 32px; transition: color 0.2s;
    }
    .back-link:hover { color: var(--umber); }
    .back-link i { font-size: 16px; }

    .detail-header {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 16px;
        flex-wrap: wrap; margin-bottom: 8px;
    }
    .detail-ref {
        font-family: 'Cormorant Garamond', serif;
        font-size: 48px; font-weight: 600;
        color: var(--umber); line-height: 1.1;
    }
    .detail-placed {
        font-size: 15px; color: var(--umber);
        opacity: 0.55; margin-top: 6px; letter-spacing: 0.02em;
    }
    .cancel-btn {
        font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;
        color: #8c2828; background: rgba(140,40,40,0.08);
        border: 1.5px solid rgba(140,40,40,0.25);
        padding: 10px 22px; border-radius: 999px;
        cursor: pointer; font-family: 'Jost', sans-serif; font-weight: 400;
        transition: background 0.2s, border-color 0.2s;
        white-space: nowrap; align-self: center;
    }
    .cancel-btn:hover { background: rgba(140,40,40,0.15); border-color: rgba(140,40,40,0.5); }

    .detail-card {
        background: var(--champagne);
        border: 1.5px solid rgba(75,54,33,0.18);
        border-radius: 20px;
        padding: 40px 44px;
        margin-bottom: 20px;
        transition: box-shadow 0.25s, transform 0.25s;
    }
    .detail-card:hover {
        box-shadow: 0 12px 40px rgba(75,54,33,0.12);
        transform: translateY(-2px);
    }
    .card-label {
        font-size: 11px; font-weight: 500; letter-spacing: 0.2em;
        text-transform: uppercase; color: var(--olive);
        margin-bottom: 28px;
        display: flex; align-items: center; gap: 8px;
    }
    .card-label::after {
        content: ''; flex: 1; height: 1px;
        background: rgba(75,54,33,0.15);
    }

    .cancelled-notice {
        display: flex; align-items: center; gap: 16px;
        padding: 20px 24px;
        background: rgba(140,40,40,0.08);
        border: 1.5px solid rgba(140,40,40,0.22);
        border-radius: 14px;
    }
    .cancelled-notice-icon {
        width: 48px; height: 48px; border-radius: 50%;
        background: rgba(140,40,40,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .cancelled-notice-icon i { font-size: 22px; color: #8c2828; }
    .cancelled-notice h4 { font-size: 16px; font-weight: 500; color: #8c2828; margin-bottom: 3px; }
    .cancelled-notice p  { font-size: 14px; color: rgba(140,40,40,0.65); }

    /* ── PROGRESS ── */
    .progress-wrap {
        position: relative;
        display: flex; align-items: flex-start;
        justify-content: space-between;
        /* no padding-top — track top is calculated from this container */
    }

    .progress-track {
        position: absolute;
        top: 30px;                   /* exact center of 60px dot */
        left: calc(10% + 30px);      /* starts at right edge of first dot center */
        right: calc(10% + 30px);     /* ends at left edge of last dot center */
        height: 2px;
        background: rgba(75,54,33,0.18);
        z-index: 0;
    }
    .progress-fill {
        position: absolute; top: 0; left: 0;
        height: 100%; background: var(--olive);
        transition: width 0.6s ease;
    }

    .progress-step {
        position: relative; z-index: 2;   /* above track */
        display: flex; flex-direction: column;
        align-items: center; text-align: center;
        width: 20%; gap: 14px;
    }

    .step-dot {
        width: 60px; height: 60px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: all 0.3s;
        position: relative; z-index: 2;
    }

    /* DONE — solid olive, white filled checkmark */
    .step-dot--done {
        background: var(--olive);
        box-shadow: 0 4px 14px rgba(128,128,0,0.3);
    }
    .step-dot--done i {
        color: #fff;
        font-size: 28px;
    }

    /* CURRENT — solid umber, champagne icon so it pops */
    .step-dot--current {
        background: var(--umber);
        box-shadow: 0 0 0 8px rgba(75,54,33,0.12), 0 4px 16px rgba(75,54,33,0.25);
    }
    .step-dot--current i {
        color: var(--champagne);   /* warm cream — high contrast on dark umber */
        font-size: 28px;
    }

    /* FUTURE — solid ivory so line is hidden behind it, muted border + icon */
    .step-dot--future {
        background: var(--ivory);
        border: 2px solid rgba(75,54,33,0.2);
    }
    .step-dot--future i {
        color: rgba(75,54,33,0.3);
        font-size: 26px;
    }

    /* show/hide logic — done shows filled check, others show step icon */
    .step-dot--done .ph-fill.ph-check-circle { display: block; }
    .step-dot--done .step-ph                  { display: none;  }
    .step-dot:not(.step-dot--done) .ph-fill.ph-check-circle { display: none;  }
    .step-dot:not(.step-dot--done) .step-ph                  { display: block; }

    .step-label {
        font-size: 12px; letter-spacing: 0.04em;
        line-height: 1.4; font-weight: 400;
    }
    .step-label--done    { color: var(--olive); font-weight: 500; }
    .step-label--current { color: var(--umber); font-weight: 700; }
    .step-label--future  { color: rgba(75,54,33,0.38); }

    /* ── TABLE ── */
    .items-table { width: 100%; border-collapse: collapse; }
    .items-table thead tr { border-bottom: 1.5px solid rgba(75,54,33,0.2); }
    .items-table th {
        font-size: 11px; font-weight: 500; letter-spacing: 0.14em;
        text-transform: uppercase; color: var(--umber); opacity: 0.5;
        padding: 0 0 16px; text-align: right;
    }
    .items-table th:first-child { text-align: left; }
    .items-table tbody tr { border-bottom: 1px solid rgba(75,54,33,0.1); }
    .items-table tbody tr:last-child { border-bottom: none; }
    .items-table tbody tr:hover { background: rgba(75,54,33,0.03); }
    .items-table td {
        padding: 18px 0; font-size: 15px;
        color: var(--umber); text-align: right; vertical-align: middle;
    }
    .items-table td:first-child { text-align: left; font-weight: 500; }
    .items-table td.muted { opacity: 0.55; }
    .items-table tfoot tr { border-top: 2px solid rgba(75,54,33,0.22); }
    .items-table tfoot td {
        padding: 20px 0 0; font-size: 17px;
        color: var(--umber); text-align: right; font-weight: 700;
    }
    .items-table tfoot td:first-child {
        text-align: left; opacity: 0.5; font-weight: 400; font-size: 15px;
    }

    /* ── DELIVERY ── */
    .delivery-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
    .delivery-field label {
        display: block; font-size: 11px; font-weight: 500;
        letter-spacing: 0.18em; text-transform: uppercase;
        color: var(--olive); margin-bottom: 9px;
    }
    .delivery-field p { font-size: 15px; color: var(--umber); line-height: 1.7; }

    .card-divider { height: 1px; background: rgba(75,54,33,0.12); margin: 0 0 28px; }

    @media (max-width: 640px) {
        .detail-wrap  { padding: 100px 20px 60px; }
        .detail-card  { padding: 24px 20px; }
        .detail-ref   { font-size: 34px; }
        .step-dot     { width: 44px; height: 44px; }
        .step-dot i   { font-size: 19px !important; }
        .step-label   { font-size: 10px; }
        .delivery-grid { grid-template-columns: 1fr; gap: 22px; }
        .progress-track { top: 22px; left: calc(10% + 22px); right: calc(10% + 22px); }
    }
</style>

<div class="detail-wrap">

    <a href="{{ route('shop.orders') }}" class="back-link">
        <i class="ph ph-arrow-left"></i>
        My Orders
    </a>

    {{-- Header --}}
    <div class="detail-header">
        <div>
            <h1 class="detail-ref">
                #{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}
            </h1>
            <p class="detail-placed">
                Placed {{ $order->created_at->diffForHumans() }} · {{ $order->created_at->format('d M Y') }}
            </p>
        </div>
        @if(!$cancelled && $currentIndex !== false && $currentIndex < 3)
            <form action="{{ route('shop.orders.cancel', $order) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this order?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="cancel-btn">
                    <i class="ph ph-x-circle" style="margin-right:5px;"></i>Cancel Order
                </button>
            </form>
        @endif
    </div>

    {{-- Status card --}}
    <div class="detail-card" style="margin-top: 36px;">
        <p class="card-label">Order Status</p>

        @if($cancelled)
            <div class="cancelled-notice">
                <div class="cancelled-notice-icon">
                    <i class="ph ph-x-circle"></i>
                </div>
                <div>
                    <h4>Order Cancelled</h4>
                    <p>This order was cancelled and will not be processed.</p>
                </div>
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
                        $dotClass   = $isDone    ? 'step-dot--done'
                                    : ($isCurrent ? 'step-dot--current'
                                                  : 'step-dot--future');
                        $labelClass = $isDone    ? 'step-label--done'
                                    : ($isCurrent ? 'step-label--current'
                                                  : 'step-label--future');
                    @endphp
                    <div class="progress-step">
                        <div class="step-dot {{ $dotClass }}">
                            {{-- done: checkmark --}}
                            <i class="ph-fill ph-check-circle"></i>
                            {{-- not done: step icon --}}
                            <i class="ph-light {{ $stepIcons[$step] }} step-ph"></i>
                        </div>
                        <p class="step-label {{ $labelClass }}">{{ $stepLabels[$step] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Items --}}
    <div class="detail-card">
        <p class="card-label">Items Ordered</p>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="muted">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="muted">{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Order Total</td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Delivery --}}
    <div class="detail-card">
        <p class="card-label">Delivery Details</p>
        <div class="delivery-grid">
            <div class="delivery-field">
                <label>Delivery Address</label>
                <p>{{ $order->delivery_address }}</p>
            </div>
            @if($order->notes)
            <div class="delivery-field">
                <label>Order Notes</label>
                <p>{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection