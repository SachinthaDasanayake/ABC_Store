<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadProductRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'category' => 'required|exists:categories,id', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The product title is required.',
            'title.string' => 'The product title must be a valid string.',
            'title.max' => 'The product title must not exceed 255 characters.',
            'description.required' => 'The product description is required.',
            'description.string' => 'The product description must be a valid string.',
            'price.required' => 'The product price is required.',
            'price.numeric' => 'The product price must be a number.',
            'price.min' => 'The product price must be at least 0.',
            'qty.required' => 'The product quantity is required.',
            'qty.integer' => 'The product quantity must be an integer.',
            'qty.min' => 'The product quantity must be at least 0.',
            'category.required' => 'The product category is required.',
            'category.exists' => 'The selected category does not exist.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image must not exceed 2MB in size.',
        ];
    }
}