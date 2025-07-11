<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('id'); // O 'user' si usas {user} en la ruta

        return [
            "first_name" => ['required', 'min:3'],
            "last_name" => ['required', 'min:3'],
            "username" => [
                'required',
                'min:3',
                Rule::unique('users', 'username')->ignore($id),
            ],
            "email" => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            "password" => ['nullable', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El :attribute es requerido',
            'first_name.min' => 'El :attribute debe tener al menos :min caracteres',

            'last_name.required' => 'El :attribute es requerido',
            'last_name.min' => 'El :attribute debe tener al menos :min caracteres',

            'username.required' => 'El :attribute es requerido',
            'username.min' => 'El :attribute debe tener al menos :min caracteres',
            'username.unique' => 'El :attribute ya fue tomado',

            'email.required' => 'El :attribute es requerido',
            'email.email' => 'El :attribute debe ser una dirección de correo válida',
            'email.unique' => 'El :attribute ya fue tomado',

            'password.required' => 'La :attribute es requerida',
            'password.min' => 'La :attribute debe tener al menos :min caracteres',
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
            'message' => 'Errores de validación',
            'errors' => $validator->errors()
        ], 422));
    }
}
