<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
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
    public function rules(): array
    {

        return [
            "first_name" => ['required', 'min:3'],
            "last_name" => ['required', 'min:3'],
            "username" => ['required', 'min:3', 'unique:users,username'],
            "email" => ['required', 'email', 'unique:users,email'],
            "password" => ['required', 'min:6'],
        ];
    }


    public function attributes()
    {
        return [
            'first_name' => 'nombre',
            'last_name' => 'apellido',
            'username' => 'usuario',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ];
    }


    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}