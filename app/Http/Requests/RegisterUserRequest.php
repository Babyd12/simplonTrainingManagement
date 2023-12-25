<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
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

     protected function prepareForValidation()
     {
         $this->merge([
             'password' => Hash::make($this->input('password')),
             'status' => 'user'
         ]);
     }

    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'firstName' => ['required','string'],
            'residence' => ['required','string'],
            'password' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email', ],
            'profilePicture' => ['string', 'nullable'],  
            'levelOfStudy' => ['required', 'string'],
            'status' => ['required', 'string'],

        ];

    }
   

    public function failedValidation(Validator $validator)
    {
        $statusCode = 422; // Default status code

        // Check for specific validation errors and set status code accordingly
        if ($validator->errors()->has('email') && $validator->errors()->first('email') === 'The email has already been taken.') {
            $statusCode = 409; // Conflict status code for duplicate email
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => true,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()
        ], $statusCode));
    }



}
