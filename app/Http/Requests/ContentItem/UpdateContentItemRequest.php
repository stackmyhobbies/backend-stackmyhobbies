<?php

namespace App\Http\Requests\ContentItem;

use App\Enums\DayOfWeek;
use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateContentItemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    /* TODO ELIMINAR EL USER DE LA REQUEST */
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
            'segment_subtype' => ['nullable', Rule::in(SubSegmentType::values())],
            'segment_subnumber' => ['nullable', 'integer'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'viewing_started_at' => ['required', 'date'],
            'viewing_finished_at' => ['nullable', 'date', 'after_or_equal:viewing_started_at'],
            'aired_from' => ['nullable', 'date'],
            'aired_to' => ['nullable', 'date'],
            'current_progress' => ['required', 'integer', 'min:0'],
            'total_progress' => ['required', 'integer', 'min:0'],
            'progress_unit' => ['required', Rule::in(ProgressUnit::values())],
            'rating' => ['nullable', 'integer', 'between:1,10'],
            'notes' => ['nullable', 'string'],
            'tags' => ['required', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'day_of_week' => ['nullable', Rule::in(DayOfWeek::values())],
        ];
    }

    public function attributes(): array
    {
        return [

            'title' => 'título',
            'description' => 'descripción',
            'content_type_id' => 'tipo de contenido',
            'image_url' => 'URL de la imagen',
            'viewing_started_at' => 'fecha de inicio de visualización',
            'viewing_finished_at' => 'fecha de fin de visualización',
            'aired_from' => 'fecha de inicio de emisión',
            'aired_to' => 'fecha de fin de emisión',
            'current_progress' => 'progreso actual',
            'total_progress' => 'progreso total',
            'rating' => 'calificación',
            'notes' => 'notas',
            'status' => 'estado activo',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        dd($validator);

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ]));
    }
}
