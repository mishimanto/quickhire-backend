<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_id' => [
    'required',
    function ($attribute, $value, $fail) {
        $exists = \App\Models\Job::where('id', $value)->exists();
        if (!$exists) {
            $fail('The selected job does not exist.');
        }
    },
],
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'resume_link' => 'required|url|max:500',
            'cover_note'  => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'job_id.exists'      => 'The selected job does not exist.',
            'email.email'        => 'Please provide a valid email address.',
            'resume_link.url'    => 'Resume link must be a valid URL.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
        ], 422));
    }
}