<?php

namespace App\Http\Requests\ContentItem;

use App\Models\ContentItem;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateContentItemProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_progress' => ['required', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'current_progress' => 'progreso actual',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $itemId = $this->route('id');

            if (! $itemId) {
                return;
            }

            $item = ContentItem::find($itemId);

            if (! $item) {
                $validator->errors()->add('id', 'El item no existe');
                return;
            }

            $value = $this->input('current_progress');

            if ($value !== null && $item->total_progress > 0 && $value > $item->total_progress) {
                $validator->errors()->add(
                    'current_progress',
                    "El progreso ({$value}) no puede superar el total ({$item->total_progress})"
                );
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));
    }
}
