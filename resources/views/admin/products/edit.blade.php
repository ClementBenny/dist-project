@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-green-600 hover:text-green-800 text-sm">← Back to Products</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-1">Edit — {{ $product->name }}</h1>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST"
      enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    @include('admin.products._form')
    <div class="mt-6">
        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Save Changes
        </button>
    </div>
</form>
@endsection