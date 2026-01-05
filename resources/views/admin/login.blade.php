@extends('layouts.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div
            class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md transform hover:scale-105 transition-all duration-300 search-box">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Admin Login</h2>
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-all duration-300">Login</button>
            </form>
        </div>
    </div>

@endsection
