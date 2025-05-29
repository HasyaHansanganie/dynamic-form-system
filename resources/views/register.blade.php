@extends('layouts.app')

@section('content')

<!-- Page Heading: Shows 'Register' or 'Update' based on context -->
<h2 class="text-2xl font-bold text-gray-800 mb-6">
    {{ isset($submission) ? 'Update' : 'Register' }} for {{ $country->name }}
</h2>

<!-- Display Success Message -->
@if(session('success'))
<div id="success-message" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<!-- No Fields Available -->
@if($fields->isEmpty())
<div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded mb-4">
    No form fields are available for {{ $country->name }} at the moment. Please check back later.
</div>

<!-- Back Button -->
<div class="flex gap-4">
    <a href="{{ url('/') }}"
        class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
        Back to Home Page
    </a>
</div>

@else

<!-- Start Form -->
<form method="POST"
    action="{{ isset($submission) ? url("/register/{$country->id}/edit/{$submission->id}") : url("/register/{$country->id}") }}"
    class="space-y-4">
    @csrf
    @if(isset($submission))
    @method('PUT')
    @endif

    <!-- Loop Through Dynamic Fields -->
    @foreach ($fields as $field)
    <div>
        <label class="block font-semibold mb-1">
            {{ $field->label }} @if($field->is_required)<span class="text-red-600">*</span>@endif
        </label>

        @php
        $inputName = 'field_' . $field->id;
        $value = old($inputName, $fieldValues[$field->id] ?? '');
        @endphp

        <!-- Handle Dropdown Input -->
        @if ($field->type === 'dropdown')
        @php
        $options = $field->options ? json_decode($field->options, true) : [];
        @endphp
        <select name="{{ $inputName }}"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Select --</option>
            @foreach($options as $option)
            <option value="{{ $option }}" @if($value==$option) selected @endif>{{ $option }}</option>
            @endforeach
        </select>

        <!-- Handle Textarea Input -->
        @elseif($field->type === 'textarea')
        <textarea name="{{ $inputName }}"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $value }}</textarea>

        <!-- Handle Other Input Types -->
        @else
        <input type="{{ $field->type }}"
            name="{{ $inputName }}"
            value="{{ $value }}"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @endif

        <!-- Validation Error from Laravel's @error -->
        @error($inputName)
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror

        <!-- Custom Validation Errors (if passed manually) -->
        @if(isset($validationErrors[$inputName]))
        <div class="text-red-600 text-sm mt-1">{{ $validationErrors[$inputName] }}</div>
        @endif
    </div>
    @endforeach

    <!-- Submit and Cancel Buttons -->
    <div class="flex gap-4">
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            {{ isset($submission) ? 'Update' : 'Submit' }}
        </button>

        <a href="{{ url('/') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
            Back to Home Page
        </a>
    </div>
</form>

<!-- Auto-fade Success Message Script -->
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

@endif
@endsection