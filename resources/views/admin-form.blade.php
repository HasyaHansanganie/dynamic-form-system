@extends('layouts.app')

@section('content')

<!-- Page Title -->
<h1 class="text-2xl font-bold mb-4">Manage Form for {{ $country->name }}</h1>

<!-- Display Success Message -->
@if(session('success'))
<div id="success-message" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<!-- Form to Add New Field -->
<form method="POST" action="{{ url('/admin/' . $country->id) }}" class="space-y-4 mb-8">
    @csrf

    <!-- Input: Field Label -->
    <div>
        <label class="block font-semibold mb-1">Label</label>
        <input name="label" required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Select: Field Type -->
    <div>
        <label class="block font-semibold mb-1">Type</label>
        <select name="type" class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="text">Text</option>
            <option value="number">Number</option>
            <option value="date">Date</option>
            <option value="dropdown">Dropdown</option>
        </select>
    </div>

    <!-- Checkbox: Is Required -->
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_required" id="is_required">
        <label for="is_required" class="font-semibold">Required Field</label>
    </div>

    <!-- Select: Category -->
    <div>
        <label class="block font-semibold mb-1">Category</label>
        <select name="category" class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="general">General</option>
            <option value="identity">Identity</option>
            <option value="bank">Bank</option>
        </select>
    </div>

    <!-- Input: Dropdown Options -->
    <div>
        <label class="block font-semibold mb-1">Dropdown Options (comma separated)</label>
        <input name="options"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Submit Button -->
    <div>
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            Add Field
        </button>
    </div>
</form>

<!-- List of Existing Fields -->
<h2 class="text-xl font-bold text-gray-800 mb-2">Existing Fields</h2>
<ul class="space-y-2">
    @foreach($fields as $field)
    <li class="bg-gray-100 rounded px-4 py-2 flex justify-between items-center">
        <div>
            <span class="font-semibold">{{ $field->label }}</span>
            <span class="text-sm text-gray-600">({{ $field->type }} | {{ $field->category }})</span>
        </div>
        <div class="flex gap-2">
            <!-- Edit Button -->
            <a href="{{ url("/admin/{$country->id}/field/{$field->id}/edit") }}"
                class="text-blue-600 hover:underline">Edit</a>

            <!-- Delete Form -->
            <form action="{{ url("/admin/{$country->id}/field/{$field->id}") }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Delete</button>
            </form>
        </div>
    </li>
    @endforeach
</ul>

<!-- Back to Home Page Button -->
<div class="mt-10">
    <a href="{{ url('/') }}" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">Back to Home Page</a>
</div>

<!-- Script: Fade Out Success Message -->
<script>
    setTimeout(() => {
        const el = document.getElementById('success-message');
        if (el) {
            el.style.transition = "opacity 1s ease-out";
            el.style.opacity = 0;
            setTimeout(() => el.remove(), 1000);
        }
    }, 3000);
</script>

@endsection