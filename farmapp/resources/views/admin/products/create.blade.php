@extends('layouts.admin')

@section('page-title', 'Add product')

@section('content')
<div class="max-w-2xl">

    <a href="{{ route('admin.products.index') }}"
       class="text-sm text-gray-500 hover:text-gray-700 mb-6 inline-block">← Back to products</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
                        <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulk price (₹) <span class="text-gray-400 font-normal">optional</span></label>
                        <input type="number" name="bulk_price" value="{{ old('bulk_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('bulk_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <select name="unit"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['kg', 'g', 'litre', 'ml', 'bunch', 'dozen', 'each', 'box'] as $unit)
                                <option value="{{ $unit }}" {{ old('unit') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                        @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock quantity</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-green-600 rounded border-gray-300">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active — visible to customers</label>
                </div>

            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                    Save product
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection