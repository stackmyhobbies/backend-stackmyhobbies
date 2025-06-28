<?php

namespace App\Http\Requests\ContentType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreContentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
                Rule::unique('content_types', 'name')
            ],

        ];
    }


    public function messages()
    {
        return [
            'name.required' => ':attribute es requerido',
            'name.unique' => 'El :attribute ya fue tomado',
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
}
