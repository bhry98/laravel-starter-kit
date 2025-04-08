<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\users\authentication;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Bhry98\LaravelStarterKit\Models\users\UsersVerifyCodesModel;
use Illuminate\Foundation\Http\FormRequest;

class UsersVerifyOtpRequest extends FormRequest
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
        $loginWay = bhry98_app_settings(key: "default_user_forgot_password_way");
        switch ($loginWay) {
            case "phone_number":
                $roles["phone_number"] = ["required", "string", "exists:" . UsersCoreUsersModel::TABLE_NAME . ",phone_number"];
                break;
            default:
                $roles["email"] = ["required", "string", "email", "exists:" . UsersCoreUsersModel::TABLE_NAME . ",email"];
        }
        $roles["otp"] = ["required", "exists:" . UsersVerifyCodesModel::TABLE_NAME . ",verify_code"];
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
