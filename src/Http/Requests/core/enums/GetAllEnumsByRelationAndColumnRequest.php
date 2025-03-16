<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\core\enums;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Illuminate\Foundation\Http\FormRequest;

class GetAllEnumsByRelationAndColumnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        return $this->merge([
            "pageNumber" => $this->pageNumber ?? 1,
            "perPage" => $this->perPage ?? 10,
            "enum_type" => request('enum_type')
        ]);
    }

    public function rules(): array
    {
        return [
            "pageNumber" => [
                "nullable",
                "numeric",
            ],
            "perPage" => [
                "nullable",
                "numeric",
                "between:5,50",
            ],
            "searchForWord" => [
                "nullable",
                "string",
                "between:1,50",
            ],
            "enum_type" => [
                "required",
                "string",
                "in:" . implode(",", array_keys(CoreEnumsModel::ENUMS_MODELS))
            ]
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        if ($this->expectsJson()) {
            $errors = collect((new \Illuminate\Validation\ValidationException($validator))->errors())->mapWithKeys(function ($messages, $key) {
                return [self::attributes()[$key] ?? $key => $messages];
            })->toArray();
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                bhry98_response_validation_error(
                    data: $errors,
                    message: (new \Illuminate\Validation\ValidationException($validator))->getMessage()
                )
            );
        }
        parent::failedValidation($validator);
    }
}
