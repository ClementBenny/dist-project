@extends('layouts.admin')

@section('page-title', 'Edit Category')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-green-600 hover:text-green-800 text-sm">← Back to Categories</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-1">Edit — {{ $category->name }}</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-md p-6">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" autofocus
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-400 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Slug</label>
            <p class="font-mono text-xs text-gray-400 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                {{ $category->slug }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Slug will update automatically when you save.</p>
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition-colors">
            Save Changes
        </button>
    </form>
</div>

@endsection