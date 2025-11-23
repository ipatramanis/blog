<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostListRequest extends FormRequest
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
            'filter_by.author_id' => ['nullable', 'integer'],
            'filter_by.category_id' => ['nullable', 'integer'],
            'filter_by.tags' => ['nullable', 'array'],
            'filter_by.tags.*' => ['nullable', 'integer'],
        ];
    }
}
