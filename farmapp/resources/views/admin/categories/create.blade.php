@extends('layouts.admin')

@section('page-title', 'Add Category')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-green-600 hover:text-green-800 text-sm">← Back to Categories</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-1">Add Category</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-md p-6">
    <form action="{{ route('admin.categories.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" autofocus
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-400 @enderror"
                   placeholder="e.g. Vegetables">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-400 mt-1">The slug will be generated automatically.</p>
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition-colors">
            Create Category
        </button>
    </form>
</div>

@endsection