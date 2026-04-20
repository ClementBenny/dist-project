@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')

    {{-- Success message --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header row --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">{{ $products->total() }} products</p>
        <a href="{{ route('admin.products.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
            Add product
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Unit</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Price</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Bulk price</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Stock</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->unit }}</td>
                    <td class="px-6 py-4 text-gray-900">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $product->bulk_price ? '₹'.number_format($product->bulk_price, 2) : '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-900">{{ $product->stock }}</td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-medium">Active</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-medium">Hidden</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-sm text-blue-600 hover:underline mr-4">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">
                        No products yet. Add your first one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="mt-6">{{ $products->links() }}</div>
    @endif

@endsection