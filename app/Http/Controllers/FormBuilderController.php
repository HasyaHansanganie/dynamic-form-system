<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\FormField;
use Illuminate\Support\Facades\Log;

class FormBuilderController extends Controller
{
    // Show form builder for a selected country
    public function show(Country $country)
    {
        $fields = $country->formFields()->orderBy('category')->get();
        return view('admin-form', compact('country', 'fields'));
    }

    // Store a new field for the selected country
    public function store(Request $request, Country $country)
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'type' => 'required|in:text,number,date,dropdown',
            'category' => 'required|in:general,identity,bank',
            'options' => 'nullable|string',
        ]);

        $field = $country->formFields()->create([
            'label' => $validated['label'],
            'type' => $validated['type'],
            'is_required' => $request->has('is_required'),
            'category' => $validated['category'],
            'options' => $validated['type'] === 'dropdown' && !empty($validated['options'])
                ? json_encode(array_map('trim', explode(',', $validated['options'])))
                : null,
        ]);

        return redirect()->back()->with('success', 'Field added successfully.');
    }

    // Show edit form for a specific field
    public function edit(Country $country, FormField $field)
    {
        return view('edit-field', compact('country', 'field'));
    }

    // Update the selected field
    public function update(Request $request, Country $country, FormField $field)
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'type' => 'required|in:text,number,date,dropdown',
            'category' => 'required|in:general,identity,bank',
            'options' => 'nullable|string',
        ]);

        $field->update([
            'label' => $validated['label'],
            'type' => $validated['type'],
            'is_required' => $request->has('is_required'),
            'category' => $validated['category'],
            'options' => $validated['type'] === 'dropdown' && !empty($validated['options'])
                ? json_encode(array_map('trim', explode(',', $validated['options'])))
                : null,
        ]);

        return redirect()->route('admin.form.show', $country->id)->with('success', 'Field updated successfully.');
    }

    // Delete a form field
    public function destroy(Country $country, FormField $field)
    {
        $field->delete();
        return redirect()->back()->with('success', 'Field deleted.');
    }
}
