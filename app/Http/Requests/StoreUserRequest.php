<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
             'formationId' => $this->route('formationId'),
         ]);
     }

    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'firstName' => ['required','string'],
            'residence' => ['required','string'],
            'password' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:students,email', ],
            'profilePicture' => ['string'],
            'levelOfStudy' => ['required', 'string'],
        ];

    }


   

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => true,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()
        ]));
    }
}
