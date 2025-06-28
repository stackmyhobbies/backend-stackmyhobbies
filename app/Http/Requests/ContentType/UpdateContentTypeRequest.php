<?php

namespace App\Http\Requests\ContentType;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateContentTypeRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name' => [
                'required',
                'min:3',
                Rule::unique('content_types', 'name')->ignore($id)
            ],
            'status' => ['required', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute es requerido',
            'status.required' =>  ':attribute es requerido',
            'name.unique' => 'El :attribute ya está registrado',
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
            'data' => $validator->errors()
        ]));
    }
}
