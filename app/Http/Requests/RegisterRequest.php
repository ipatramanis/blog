<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,spoof|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'repeat_password' => 'required|string|min:8|same:password',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.email' => 'Invalid email address.',
            'email.unique' => 'Invalid email address.',
        ];
    }

    /**
     * Send http response on failed validation
     *
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        // Format error messages
        $errorMessages = [];
        foreach ($validator->errors()->getMessageBag()->getMessages() as $key => $message) {
            $errorMessages['message'][$key] = $message[0];
        }

        // Throw json response with http status
        throw new HttpResponseException(
            response()->json($errorMessages, 422)
        );
    }
}
