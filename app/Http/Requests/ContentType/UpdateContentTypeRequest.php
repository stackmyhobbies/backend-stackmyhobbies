<?php

namespace App\Http\Requests\ContentType;

use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
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
}
