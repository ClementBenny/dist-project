@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        + Add Category
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Name</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Slug</th>
                <th class="text-left px-6 py-3 font-medium text-gray-500">Products</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                <td class="px-6 py-4 font-mono text-xs text-gray-400">{{ $category->slug }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $category->products_count }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="text-green-600 hover:text-green-800 font-medium">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete {{ $category->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-gray-400 py-12">No categories yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection