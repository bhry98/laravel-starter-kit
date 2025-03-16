<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\core\locations\cities;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCityDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        return $this->merge([
            "with" => $this->with ? explode(separator: ',', string: $this->with) : null,
            "city_code" => $this->city_code
        ]);
    }

    public function rules(): array
    {

        return [
            "with" => [
                "nullable",
                "array",
                "between:1,5",
            ],
            "with.*" => [
                "nullable",
                Rule::in(values: CoreLocationsCitiesModel::RELATIONS),
            ],
            "city_code" => [
                "required",
                "string",
                "exists:" . CoreLocationsCitiesModel::TABLE_NAME . ",code",
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
