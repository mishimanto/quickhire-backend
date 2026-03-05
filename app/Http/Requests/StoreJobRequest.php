<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'company'      => 'required|string|max:255',
            'location'     => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'category_id'  => 'nullable|exists:categories,id',
            'type'         => 'required|in:Full-time,Part-time,Remote,Contract,Internship',
            'salary_range' => 'nullable|string|max:100',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'logo_url'     => 'nullable|url',
            'is_featured'  => 'nullable|boolean',
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