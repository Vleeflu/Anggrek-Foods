@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-semibold mb-4">Login Admin</h2>
    <p class="text-sm text-gray-600 mb-4">Gunakan akun admin: <span class="font-medium">username: admin</span></p>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.perform') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required class="mt-1 w-full rounded-md border-gray-300 focus:border-brand focus:ring-brand" placeholder="admin">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="••••••••">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="rounded-md bg-brand px-4 py-2 text-white hover:bg-brand-light">Masuk</button>
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">Kembali</a>
        </div>
    </form>
</div>
@endsection
