<?php

namespace App\Http\Requests\ContentType;

use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
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
            'name' => [
                'required',
                'min:3',
                'unique:content_types,name'
            ],
            'status' => ['required', 'boolean'],
            'allowed_units' => ['required', 'array', 'min:1'],
            'allowed_units.*' => [Rule::enum(ProgressUnit::class)],
            'allowed_segment_types' => ['required', 'array', 'min:1'],
            'allowed_segment_types.*' => [Rule::enum(SegmentType::class)],
            'allowed_subsegment_types' => ['nullable', 'array'],
            'allowed_subsegment_types.*' => [Rule::enum(SubSegmentType::class)],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'status' => 'estado',
            'allowed_units' => 'unidades permitidas',
            'allowed_segment_types' => 'tipos de segmento permitidos',
            'allowed_subsegment_types' => 'tipos de subsegmento permitidos',
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
