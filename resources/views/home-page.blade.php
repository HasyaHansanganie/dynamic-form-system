@extends('layouts.app')

@section('content')
<h1 class="text-3xl mb-10 font-bold text-center">Welcome to Dynamic Form System</h1>

<form action="{{ route('role-country.redirect') }}" method="POST" class="space-y-6">
    @csrf

    <!-- Role Selection -->
    <label class="block text-gray-800 font-semibold mb-5 text-xl">Select Your Role</label>
    <div class="flex gap-6">
        <label class="flex items-center space-x-2">
            <input type="radio" name="role" value="admin" required>
            <span>Admin</span>
        </label>
        <label class="flex items-center space-x-5">
            <input type="radio" name="role" value="user" required>
            <span>User</span>
        </label>
    </div>

    <!-- Country Selection -->
    <label for="country" class="block text-gray-800 font-semibold mb-5 text-xl">Select Your Country</label>
    <select name="country" id="country" required class="w-full border rounded-lg px-4 py-2">
        <option value="">-- Choose a Country --</option>
        @foreach ($countries as $country)
        <option value="{{ $country }}">{{ $country }}</option>
        @endforeach
    </select>

    <!-- Submit Button -->
    <div class="text-center">
        <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-md">
            Continue
        </button>
    </div>
</form>
@endsection