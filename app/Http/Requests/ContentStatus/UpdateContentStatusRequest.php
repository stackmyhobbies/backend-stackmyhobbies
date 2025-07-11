<?php

namespace App\Http\Requests\ContentStatus;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateContentStatusRequest extends FormRequest
{

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
            'name' => [
                'required',
                'min:3'
            ],
            'status' => ['required', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute es requerido',
            'status.required' =>  ':attribute es requerido',
            'status.boolean' => 'El :attribute debe ser true o false'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'status' => 'estado'
        ];
    }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ]));
    }
}
