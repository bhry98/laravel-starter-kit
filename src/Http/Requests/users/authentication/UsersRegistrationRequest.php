<?php

namespace Bhry98\LaravelStarterKit\Http\Requests\users\authentication;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Illuminate\Foundation\Http\FormRequest;

class UsersRegistrationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $fixedData["country_id"] = $this->country;
        $fixedData["governorate_id"] = $this->governorate;
        $fixedData["city_id"] = $this->city;
        $fixedData["gender_id"] = $this->gender;
        $fixedData["redirect_link"] = is_null($this->redirect_link) ? session(key: "redirect_link") : $this->redirect_link;
        $fixedData["display_name"] = is_null($this->display_name) ? $this->first_name . " " . $this->last_name : $this->display_name;
        $fixedData["username"] = is_null($this->username) ? $this->email : $this->username;
        $fixedData["user_type"] = bhry98_app_settings(key: 'default_user_type_in_registration') ?? $this->user_type;
        return $this->merge($fixedData);
    }

    public function rules(): array
    {
        $roles["username"] = [
            "required",
            "string",
            "unique:" . UsersCoreUsersModel::TABLE_NAME . ",username",
        ];
        $roles["country_id"] = [
            "nullable",
            "uuid",
            "exists:" . CoreLocationsCountriesModel::TABLE_NAME . ",code",
        ];
        $roles["governorate_id"] = [
            "nullable",
            "uuid",
            "exists:" . CoreLocationsGovernoratesModel::TABLE_NAME . ",code",
        ];
        $roles["city_id"] = [
            "nullable",
            "uuid",
            "exists:" . CoreLocationsCitiesModel::TABLE_NAME . ",code",
        ];
        $roles["email"] = [
            "required",
            "email",
            "unique:" . UsersCoreUsersModel::TABLE_NAME . ",email",
        ];
        $roles["password"] = [
            "required",
            "string",
            "between:8,50",
            "confirmed",
        ];
        $roles["redirect_link"] = [
            "nullable"
        ];
        $roles["display_name"] = [
            "nullable",
            "string",
            "max:50",
        ];
        $roles["first_name"] = [
            "required",
            "string",
            "max:50",
        ];
        $roles["last_name"] = [
            "required",
            "string",
            "max:50",
        ];
        $roles["birthdate"] = [
            "nullable",
            "date",
            "before:" . date('Y') - 10,
        ];
        $roles["national_id"] = [
            config("bhry98-starter.validations.users.national_id.required", false) ? "required" : "nullable",
            "numeric",
            "digits:14",
            "unique:" . UsersCoreUsersModel::TABLE_NAME . ",national_id",
        ];
        $roles["phone_number"] = [
            config("bhry98-starter.validations.users.phone_number.required", false) ? "required" : "nullable",
            "numeric",
            "digits:11",
            "unique:" . UsersCoreUsersModel::TABLE_NAME . ",phone_number",

        ];
        $roles["gender_id"] = [
            "required",
            "string",
            "exists:" . CoreEnumsModel::TABLE_NAME . ",code",

        ];
        $roles["user_type"] = [
            is_null(bhry98_app_settings(key: 'default_user_type_in_registration')) ? "required" : "nullable",
            "string",
            "exists:" . CoreEnumsModel::TABLE_NAME . ",code",

        ];
        return $roles;
    }

    public function attributes(): array
    {
        return [
            "country_id" => "country",
            "governorate_id" => "governorate",
            "city_id" => "city",
        ];
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
