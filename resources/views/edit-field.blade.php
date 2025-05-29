@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<h1 class="text-2xl font-bold mb-4 text-gray-800">Edit Field for {{ $country->name }}</h1>

<!-- Edit Field Form -->
<form method="POST" action="{{ url("/admin/{$country->id}/field/{$field->id}") }}" class="space-y-4">
    @csrf
    @method('PUT')

    <!-- Label Input -->
    <div>
        <label class="block font-semibold mb-1">Label</label>
        <input name="label" value="{{ $field->label }}" required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Field Type Selector -->
    <div>
        <label class="block font-semibold mb-1">Type</label>
        <select name="type" class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="text" {{ $field->type == 'text' ? 'selected' : '' }}>Text</option>
            <option value="number" {{ $field->type == 'number' ? 'selected' : '' }}>Number</option>
            <option value="date" {{ $field->type == 'date' ? 'selected' : '' }}>Date</option>
            <option value="dropdown" {{ $field->type == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
        </select>
    </div>

    <!-- Required Field Checkbox -->
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_required" id="is_required" {{ $field->is_required ? 'checked' : '' }}>
        <label for="is_required" class="font-semibold">Required Field</label>
    </div>

    <!-- Category Selector -->
    <div>
        <label class="block font-semibold mb-1">Category</label>
        <select name="category" class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="general" {{ $field->category == 'general' ? 'selected' : '' }}>General</option>
            <option value="identity" {{ $field->category == 'identity' ? 'selected' : '' }}>Identity</option>
            <option value="bank" {{ $field->category == 'bank' ? 'selected' : '' }}>Bank</option>
        </select>
    </div>

    <!-- Dropdown Options Input -->
    <div>
        <label class="block font-semibold mb-1">Dropdown Options (comma-separated)</label>
        <input name="options" value="{{ is_array(json_decode($field->options, true)) ? implode(',', json_decode($field->options, true)) : '' }}"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Submit and Back Buttons -->
    <div class="flex gap-4">
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            Update Field
        </button>

        <a href="{{ route('admin.form.show', $country->id) }}"
            class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
            Back to Form
        </a>
    </div>
</form>
@endsection