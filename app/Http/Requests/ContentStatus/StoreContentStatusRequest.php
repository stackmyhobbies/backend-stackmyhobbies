<?php

namespace App\Http\Requests\ContentStatus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreContentStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            //
            'name' => [
                'required',
                'min:3',
            ],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute es requerido',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'data' => $validator->errors(),
            'message' => 'Validation errors'
        ]));
    }
}
