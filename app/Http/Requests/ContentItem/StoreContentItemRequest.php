<?php

namespace App\Http\Requests\ContentItem;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use Illuminate\Validation\Rule;

class StoreContentItemRequest extends FormRequest
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
    /* TODO APLICAR REGLA GNERAL ELIMINAR EL USER_ID DE LA REQUEST*/
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string'],
            'content_type_id' => ['required', 'exists:content_types,id'],
            'progress_status_id' => ['required', 'exists:progress_statuses,id'],
            'segment_type' => ['nullable', Rule::in(SegmentType::values())],
            'segment_number' => ['nullable', 'integer'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'current_progress' => ['required', 'integer', 'min:0'],
            'total_progress' => ['required', 'integer', 'min:0'],
            'progress_unit' => ['required', Rule::in(ProgressUnit::values())],
            'rating' => ['nullable', 'integer', 'between:1,10'],
            'notes' => ['nullable', 'string'],
            'status' => ['boolean'],
            'tags' => ['required', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'] // Laravel asume true/false, puedes usar cast en el modelo
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descripción',
            'content_type_id' => 'tipo de contenido',
            'image_url' => 'URL de la imagen',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de finalización',
            'current_progress' => 'progreso actual',
            'total_progress' => 'progreso total',
            'rating' => 'calificación',
            'notes' => 'notas',
            'status' => 'estado activo',
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
