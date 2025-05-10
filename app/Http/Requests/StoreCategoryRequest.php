<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Return true to allow all users to make this request.
        // You can add authorization logic here if needed.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|string|max:50|unique:categories,category_name',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'The category name is required.',
            'category.string' => 'The category name must be a valid string.',
            'category.max' => 'The category name must not exceed 50 characters.',
            'category.unique' => 'This category already exists.',
        ];
    }
}