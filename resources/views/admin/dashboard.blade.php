@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Users</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Products</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Orders</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalOrders }}</p>
        <p class="text-xs text-amber-600 mt-1">{{ $pendingOrders }} pending</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Revenue</p>
        <p class="text-3xl font-bold text-gray-400">—</p>
        <p class="text-xs text-gray-400 mt-1">Coming in Phase 3</p>
    </div>

</div>

{{-- Second row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Users by Role --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Users by Role</h2>

        @php
            $roleColours = [
                'admin'    => 'bg-purple-100 text-purple-700',
                'customer' => 'bg-blue-100 text-blue-700',
                'shop'     => 'bg-amber-100 text-amber-700',
                'staff'    => 'bg-green-100 text-green-700',
            ];
        @endphp

        <div class="space-y-3">
            @foreach(['admin', 'customer', 'shop', 'staff'] as $role)
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $roleColours[$role] }}">
                    {{ ucfirst($role) }}
                </span>
                <span class="text-sm font-medium text-gray-700">
                    {{ $usersByRole[$role] ?? 0 }}
                    <span class="text-gray-400 font-normal">{{ Str::plural('user', $usersByRole[$role] ?? 0) }}</span>
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recently Joined --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Recently Joined</h2>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-green-600 hover:text-green-800">
                View all →
            </a>
        </div>

        <div class="space-y-3">
            @foreach($recentUsers as $user)
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                </div>
                <div class="text-right">
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $roleColours[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection