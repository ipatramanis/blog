<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'title' => ['nullable', 'unique:posts', 'max:255'],
            'content' => ['nullable', 'max:500'],
            'slug' => ['nullable', 'max:150'],
            'tags' => ['nullable', 'array', 'exists:tags,id'],
        ];
    }
}
