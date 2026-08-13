@extends('layouts.admin')

@section('page-title', 'Feedback Detail')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Feedback from {{ $feedback->user->name ?? 'Unknown user' }}</div>
        <div class="a-page-sub">Submitted {{ $feedback->created_at->format('d M Y, g:i A') }}</div>
    </div>
    <a href="{{ route('admin.reports.feedback') }}" class="a-btn a-btn-ghost"><i class="ti ti-arrow-left"></i> All feedback</a>
</div>

<div class="a-card">
    <div class="a-card-body">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div style="font-size:22px;color:var(--olive);">
                @for($i = 1; $i <= 5; $i++)
                    {!! $i <= $feedback->rating ? '★' : '<span style="color:#D8D0C4;">☆</span>' !!}
                @endfor
            </div>

            @if($feedback->is_active)
                <span class="a-badge a-badge-delivered">Active — visible on site</span>
            @else
                <span class="a-badge a-badge-cancelled">Inactive — hidden from site</span>
            @endif
        </div>

        <hr class="a-divider">

        <div class="a-label">Comment</div>
        <p style="font-size:14px;color:var(--dark);line-height:1.7;margin-top:6px;">
            {{ $feedback->comment }}
        </p>

        <hr class="a-divider">

        <form method="POST" action="{{ route('admin.reports.feedback.toggle', $feedback) }}">
            @csrf
            @method('PATCH')
            @if($feedback->is_active)
                <button type="submit" class="a-btn a-btn-danger"><i class="ti ti-eye-off"></i> Deactivate — hide from site</button>
            @else
                <button type="submit" class="a-btn a-btn-olive"><i class="ti ti-eye"></i> Activate — show on site</button>
            @endif
        </form>

    </div>
</div>
@endsection