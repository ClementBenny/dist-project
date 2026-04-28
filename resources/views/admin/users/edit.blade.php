@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-green-600 hover:text-green-800 text-sm">← Back to Users</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-1">Edit User — {{ $user->name }}</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-lg p-6">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-400 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-400 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('role') border-red-400 @enderror">
                @foreach(['admin', 'customer', 'shop', 'staff'] as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition-colors">
            Save Changes
        </button>
    </form>
</div>
@endsection