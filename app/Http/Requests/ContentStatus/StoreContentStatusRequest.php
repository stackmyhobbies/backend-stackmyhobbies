<?php

namespace App\Http\Requests\ContentStatus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

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
                Rule::unique('content_statuses', 'name')
            ],

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
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ]));
    }
}