@extends('layouts.admin')

@section('page-title', 'Users Report')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Users Report</div>
        <div class="a-page-sub">{{ $rows->count() }} users</div>
    </div>
    <button type="button" class="a-btn a-btn-primary" onclick="window.print()">
        <i class="ti ti-file-type-pdf"></i> Download PDF
    </button>
</div>

<div class="a-card">
    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.reports.users') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Role</label>
                <select name="role" class="a-input">
                    <option value="">All roles</option>
                    <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="staff"    {{ request('role') === 'staff'    ? 'selected' : '' }}>Staff</option>
                </select>
            </div>

            <button type="submit" class="a-btn a-btn-primary"><i class="ti ti-filter"></i> Filter</button>

            @if(request()->filled('role'))
                <a href="{{ route('admin.reports.users') }}" class="a-btn a-btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div class="print-area">
        <div class="a-print-header">
            <h1>Farm Direct — Users Report</h1>
            <div class="a-print-meta">
                Generated {{ now()->format('d M Y, h:i A') }}
                @if(request()->filled('role')) &middot; Role: {{ ucfirst(request('role')) }} @endif
                &middot; {{ $rows->count() }} users
            </div>
        </div>

        <div style="overflow-x:auto;">
        <table class="a-table">
            <thead>
            <tr>
                <x-a-sortable-th column="name" label="Name" :sort="$sort" :direction="$direction" />
                <th>Email</th>
                <th>Role</th>
                <x-a-sortable-th column="created_at" label="Joined" :sort="$sort" :direction="$direction" />
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td><span class="a-badge a-badge-{{ $row->role }}">{{ ucfirst($row->role) }}</span></td>
                    <td>{{ $row->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="a-empty">No users found.</td></tr>
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