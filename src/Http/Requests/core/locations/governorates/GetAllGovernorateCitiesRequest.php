<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\core\locations\governorates;

use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetAllGovernorateCitiesRequest extends FormRequest
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
            "with" => $this->with ? explode(separator: ',', string: $this->with) : null,
            "governorate_code" => $this->governorate_code,
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
            "with" => [
                "nullable",
                "array",
                "between:1,5",
            ],
            "with.*" => [
                "nullable",
                Rule::in(values: CoreLocationsCitiesModel::RELATIONS),
            ],
            "governorate_code" => [
                "required",
                "string",
                "exists:" . CoreLocationsGovernoratesModel::TABLE_NAME . ",code",
            ]
        ];
    }

    public function attributes(): array
    {
        $attributes = [];
        foreach ($this->with ?? [] as $withKey => $with) {
            $attributes["with.$withKey"] = "$with";
        }
        return $attributes;
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
