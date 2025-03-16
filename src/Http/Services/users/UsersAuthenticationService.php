<?php

namespace Bhry98\LaravelStarterKit\Http\Services\users;

use Bhry98\LaravelStarterKit\Http\Services\core\enums\CoreEnumsService;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsCitiesService;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsCountriesService;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsGovernoratesService;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsersAuthenticationService
{
    function registration(array $data)
    {
        // check if user type exists
        $userTypeId = CoreEnumsService::getByCode(UUID: bhry98_app_settings(key: 'default_user_type_in_registration') ?? $data['user_type'])?->id;
        // if normal user type not found return null
        throw_if(!$userTypeId, "No user type found");
        // add normal user in database
        $data['type_id'] = $userTypeId;
        $data['country_id'] = CoreLocationsCountriesService::getDetailsByCode($data['country_id'])?->id;
        $data['governorate_id'] = CoreLocationsGovernoratesService::getDetailsByCode($data['governorate_id'])?->id;
        $data['city_id'] = CoreLocationsCitiesService::getDetailsByCode($data['city_id'])?->id;
        $data['gender_id'] = CoreEnumsService::getByCode($data['gender_id'])?->id;
        $user = UsersCoreUsersModel::create($data);
        if ($user) {
            // if added successfully add log [info] and return user
            Log::info("User registered successfully with id {$user->id}", ['user' => $user]);
            return $user;
        } else {
            // if added successfully add log [error] and return user
            Log::error("User registered field");
            return null;
        }
    }

    public function loginViaUser(UsersCoreUsersModel|\Illuminate\Contracts\Auth\Authenticatable $user): string
    {
        Auth::loginUsingId($user->id);
        $tokenResult = $user->createToken($user->code);
        return $tokenResult->plainTextToken;
    }
    public function loginViaUsernameAndPassword(array $data): string|null
    {
        if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
            $user = self::getAuthUser();
            Log::info("User login successfully via username and password with id {$user?->id}", ['user' => $user]);
            return self::loginViaUser($user);
        } else {
            Log::error("User login failed via username and password", ['credential' => $data]);
            return null;
        }
    }
    public function loginViaEmailAndPassword(array $data): string|null
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = self::getAuthUser();
            Log::info("User login successfully via email and password with id {$user?->id}", ['user' => $user]);
            return self::loginViaUser($user);
        } else {
            Log::error("User login failed via email and password", ['credential' => $data]);
            return null;
        }
    }
    public function loginViaPhoneAndPassword(array $data): string|null
    {
        if (Auth::attempt(['phone_number' => $data['phone_number'], 'password' => $data['password']])) {
            $user = self::getAuthUser();
            Log::info("User login successfully via phone_number and password with id {$user?->id}", ['user' => $user]);
            return self::loginViaUser($user);
        } else {
            Log::error("User login failed via phone_number and password", ['credential' => $data]);
            return null;
        }
    }
    public function loginViaNationalIDAndPassword(array $data): string|null
    {
        if (Auth::attempt(['national_id' => $data['national_id'], 'password' => $data['password']])) {
            $user = self::getAuthUser();
            Log::info("User login successfully via national_id and password with id {$user?->id}", ['user' => $user]);
            return self::loginViaUser($user);
        } else {
            Log::error("User login failed via national_id and password", ['credential' => $data]);
            return null;
        }
    }

    static public function getAuthUser(array|null $relations = null): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $authUser = Auth::user();
        $user = UsersCoreUsersModel::where('code', $authUser?->code);
        if ($user && $relations) {
            $user->with($relations);
        }
        return $user->first();
    }

}