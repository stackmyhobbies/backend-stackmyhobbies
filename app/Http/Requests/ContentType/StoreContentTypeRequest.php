<?php

namespace App\Http\Requests\ContentType;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
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
                'unique:content_types,name'
            ],

        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El :attribute es requerido',
            'name.min' => 'El :attribute debe tener al menos :min caracteres',
            'name.unique' => 'el :attribute fue tomado'
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

    //** for sqlite */
    // public function withValidator(Validator $validator)
    // {
    //     $validator->after(function ($validator) {
    //         $name = strtolower($this->input('name'));

    //         $exists = DB::table('content_types')
    //             ->whereRaw('LOWER(name) = ?', [$name])
    //             ->exists();

    //         if ($exists) {
    //             $validator->errors()->add('name', 'El nombre ya fue tomado');
    //         }
    //     });
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
}
