@extends('layouts.admin')

@section('page-title', 'Feedback Report')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Feedback Report</div>
        <div class="a-page-sub">{{ $rows->count() }} reviews</div>
    </div>
</div>

<div class="a-card">
    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.reports.feedback') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Rating</label>
                <select name="rating" class="a-input">
                    <option value="">All ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ (string) request('rating') === (string) $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Status</label>
                <select name="status" class="a-input">
                    <option value="">All statuses</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="a-btn a-btn-primary"><i class="ti ti-filter"></i> Filter</button>

            @if(request()->hasAny(['rating', 'status']))
                <a href="{{ route('admin.reports.feedback') }}" class="a-btn a-btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div style="overflow-x:auto;">
    <table class="a-table">
        <thead>
        <tr>
            <th>User</th>
            <x-a-sortable-th column="rating" label="Rating" :sort="$sort" :direction="$direction" />
            <th>Comment</th>
            <x-a-sortable-th column="created_at" label="Date" :sort="$sort" :direction="$direction" />
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr>
                <td>{{ $row->user->name ?? '—' }}</td>
                <td style="font-size:16px;color:var(--olive);">
                    @for($i = 1; $i <= 5; $i++)
                        {!! $i <= $row->rating ? '★' : '<span style="color:#D8D0C4;">☆</span>' !!}
                    @endfor
                </td>
                <td style="max-width:320px;">{{ Str::limit($row->comment, 100) }}</td>
                <td>{{ $row->created_at->format('d M Y') }}</td>
                <td>
                    @if($row->is_active)
                        <span class="a-badge a-badge-delivered">Active</span>
                    @else
                        <span class="a-badge a-badge-cancelled">Inactive</span>
                    @endif
                </td>
                <td class="right">
                    <a href="{{ route('admin.reports.feedback.show', $row) }}" style="color:var(--muted);">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="a-empty">No feedback found.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection