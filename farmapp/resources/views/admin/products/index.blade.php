@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Products</h1>
    <a href="{{ route('admin.products.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        + Add Product
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-500">Image</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500">Name</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500">Category</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500">Price</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500">Stock</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-2xl border border-gray-200">
                            🥬
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $product->name }}</p>
                    @if($product->description)
                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ $product->description }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">
                    {{ $product->category?->name ?? '—' }}
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">₹{{ number_format($product->price, 2) }}</p>
                    @if($product->bulk_price)
                        <p class="text-xs text-gray-400">Bulk: ₹{{ number_format($product->bulk_price, 2) }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $product->stock }} {{ $product->unit }}</td>
                <td class="px-4 py-3">
                    @if($product->is_active)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="text-green-600 hover:text-green-800 font-medium">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete {{ $product->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-gray-400 py-12">No products yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection