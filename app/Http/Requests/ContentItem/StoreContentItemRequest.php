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
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string'],
            'type_id' => ['required', 'exists:content_types,id'],
            'status_id' => ['required', 'exists:content_statuses,id'],
            'segment_type' => ['nullable', Rule::in(SegmentType::values())],
            'segment_number' => ['nullable', 'integer'],
            'image_url' => ['nullable', 'string', 'max:255'],
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
            'user_id' => 'usuario',
            'title' => 'título',
            'description' => 'descripción',
            'type_id' => 'tipo de contenido',
            'status_id' => 'estado',
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
