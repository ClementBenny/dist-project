@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500 mb-1">Total users</p>
            <p class="text-3xl font-semibold text-gray-900">—</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500 mb-1">Products</p>
            <p class="text-3xl font-semibold text-gray-900">—</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500 mb-1">Orders today</p>
            <p class="text-3xl font-semibold text-gray-900">—</p>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Recent orders will appear here once orders are set up.</p>
    </div>
@endsection