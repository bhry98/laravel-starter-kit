<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\users\me;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Illuminate\Foundation\Http\FormRequest;

class UsersGetMeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $fixedData = [];
        return $this->merge($fixedData);
    }

    public function rules(): array
    {
        $roles = [];
        return $roles;
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
