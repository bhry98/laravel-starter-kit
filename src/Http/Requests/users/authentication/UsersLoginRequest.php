<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\users\authentication;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Illuminate\Foundation\Http\FormRequest;

class UsersLoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $fixedData["redirect_link"] = is_null($this->redirect_link) ? session("redirect_link") : $this->redirect_link;
        return $this->merge($fixedData);
    }

    public function rules(): array
    {
        $loginWay = bhry98_app_settings(key: "default_user_login_way");
        switch ($loginWay) {
            case "email":
                $roles["email"] = ["required", "string", "exists:" . UsersCoreUsersModel::TABLE_NAME . ",email"];
                break;
                case "national_id":
                $roles["national_id"] = ["required", "exists:" . UsersCoreUsersModel::TABLE_NAME . ",national_id"];
                break;
            case "phone_number":
                $roles["phone_number"] = ["required", "string", "exists:" . UsersCoreUsersModel::TABLE_NAME . ",phone_number"];
                break;
            default:
                $roles["username"] = ["required", "string", "exists:" . UsersCoreUsersModel::TABLE_NAME . ",username"];
        }
        $roles["password"] = [
            "required",
            "string",
            "min:6",
        ];
        $roles["redirect_link"] = [
            "nullable"
        ];
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
