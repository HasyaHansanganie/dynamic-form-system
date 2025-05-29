<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\FormField;
use App\Models\UserSubmission;
use App\Models\SubmissionValue;

class RegistrationController extends Controller
{
    /**
     * Display the dynamic registration form for a specific country.
     * Includes validation on load based on field definitions.
     */
    public function showForm($countryId)
    {
        // Retrieve country and its fields
        $country = Country::findOrFail($countryId);
        $fields = FormField::where('country_id', $countryId)->get();

        // Load existing submission if available
        $submission = UserSubmission::where('country_id', $countryId)->first();

        // Map saved values by field ID
        $fieldValues = $submission
            ? $submission->values->pluck('value', 'form_field_id')
            : collect();

        // Initialize validation errors array
        $validationErrors = [];

        // Validate existing values based on latest field definitions
        foreach ($fields as $field) {
            $value = $fieldValues[$field->id] ?? null;
            $inputName = "field_{$field->id}";

            // Required field validation
            if ($field->is_required && ($value === null || $value === '')) {
                $validationErrors[$inputName] = "{$field->label} is required.";
                continue;
            }

            // Skip validation if not required and empty
            if (!$field->is_required && ($value === null || $value === '')) continue;

            // Type validation based on field type
            switch ($field->type) {
                case 'text':
                    if (!is_string($value)) {
                        $validationErrors[$inputName] = "{$field->label} must be text.";
                    }
                    break;

                case 'number':
                    if (!is_numeric($value)) {
                        $validationErrors[$inputName] = "{$field->label} must be a number.";
                    }
                    break;

                case 'date':
                    if (!strtotime($value)) {
                        $validationErrors[$inputName] = "{$field->label} must be a valid date.";
                    }
                    break;

                case 'dropdown':
                    $options = $field->options ? json_decode($field->options, true) : [];
                    if (!in_array($value, $options)) {
                        $validationErrors[$inputName] = "{$field->label} must be a valid option.";
                    }
                    break;

                default:
                    $validationErrors[$inputName] = "{$field->label} has an invalid field type.";
                    break;
            }
        }

        return view('register', compact('country', 'fields', 'submission', 'fieldValues', 'validationErrors'));
    }

    /**
     * Store a new user submission.
     * Builds validation rules dynamically based on form fields.
     */
    public function store(Request $request, $countryId)
    {
        $country = Country::findOrFail($countryId);
        $fields = FormField::where('country_id', $countryId)->get();

        // Build validation rules for each field
        $rules = [];
        foreach ($fields as $field) {
            $rule = [];

            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            switch ($field->type) {
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'dropdown':
                    $options = $field->options ? json_decode($field->options, true) : [];
                    $rule[] = 'in:' . implode(',', array_map('trim', $options));
                    break;
                case 'text':
                default:
                    $rule[] = 'string';
                    break;
            }

            $rules["field_{$field->id}"] = $rule;
        }

        // Validate form input
        $validated = $request->validate($rules);

        // Create new submission
        $submission = UserSubmission::create(['country_id' => $countryId]);

        // Save each submitted value
        foreach ($fields as $field) {
            $inputName = "field_{$field->id}";
            $value = $validated[$inputName] ?? null;

            SubmissionValue::create([
                'user_submission_id' => $submission->id,
                'form_field_id' => $field->id,
                'value' => $value,
            ]);
        }

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    /**
     * Update an existing user submission.
     * Validates updated values against current field definitions.
     */
    public function update(Request $request, $countryId, $submissionId)
    {
        $country = Country::findOrFail($countryId);
        $fields = FormField::where('country_id', $countryId)->get();

        // Build validation rules for update
        $rules = [];
        foreach ($fields as $field) {
            $rule = [];
            if ($field->is_required) $rule[] = 'required';

            switch ($field->type) {
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'dropdown':
                    $options = $field->options ? json_decode($field->options, true) : [];
                    $rule[] = 'in:' . implode(',', array_map('trim', $options));
                    break;
                case 'text':
                default:
                    $rule[] = 'string';
                    break;
            }

            $rules["field_{$field->id}"] = $rule;
        }

        // Validate the request
        $validated = $request->validate($rules);

        // Find the submission to update
        $submission = UserSubmission::findOrFail($submissionId);

        // Update or create each submission value
        foreach ($fields as $field) {
            $inputName = "field_{$field->id}";
            $value = $validated[$inputName] ?? null;

            SubmissionValue::updateOrCreate(
                [
                    'user_submission_id' => $submission->id,
                    'form_field_id' => $field->id,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        return redirect()->back()->with('success', 'Form updated successfully!');
    }
}
