@extends('layouts.admin')

@section('page-title', 'Products Report')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Products Report</div>
        <div class="a-page-sub">{{ $rows->count() }} products</div>
    </div>
    <button type="button" class="a-btn a-btn-primary" onclick="window.print()">
        <i class="ti ti-file-type-pdf"></i> Download PDF
    </button>
</div>

<div class="a-card">
    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.reports.products') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Category</label>
                <select name="category_id" class="a-input">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="a-btn a-btn-primary"><i class="ti ti-filter"></i> Filter</button>

            @if(request()->filled('category_id'))
                <a href="{{ route('admin.reports.products') }}" class="a-btn a-btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div class="print-area">
        <div class="a-print-header">
            <h1>Farm Direct — Products Report</h1>
            <div class="a-print-meta">
                Generated {{ now()->format('d M Y, h:i A') }}
                @if(request()->filled('category_id'))
                    @php($activeCategory = $categories->firstWhere('id', request('category_id')))
                    @if($activeCategory) &middot; Category: {{ $activeCategory->name }} @endif
                @endif
                &middot; {{ $rows->count() }} products
            </div>
        </div>

        <div style="overflow-x:auto;">
        <table class="a-table">
            <thead>
            <tr>
                <x-a-sortable-th column="name" label="Name" :sort="$sort" :direction="$direction" />
                <th>Category</th>
                <x-a-sortable-th column="price" label="Price" :sort="$sort" :direction="$direction" />
                <x-a-sortable-th column="stock" label="Stock" :sort="$sort" :direction="$direction" />
                <x-a-sortable-th column="created_at" label="Added" :sort="$sort" :direction="$direction" />
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->category->name ?? '—' }}</td>
                    <td>₹{{ number_format($row->price, 2) }}</td>
                    <td>{{ $row->stock }}</td>
                    <td>{{ $row->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="a-empty">No products found.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

<style>
    .a-print-header {
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

        .a-print-header {
            display: block;
            margin-bottom: 16px;
            border-bottom: 2px solid #4a4a3a;
            padding-bottom: 10px;
        }

        .a-print-header h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }

        .a-print-meta {
            font-size: 11px;
            color: #666;
        }

        .a-table {
            width: 100%;
            table-layout: auto;
        }

        .a-table tr {
            page-break-inside: avoid;
        }

        .a-table th {
            background: #f2f0ea !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection