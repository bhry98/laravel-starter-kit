<?php

namespace Bhry98\LaravelStarterKit\Http\Services\users;

use Bhry98\LaravelStarterKit\Console\mails\auth\SendResetPasswordOtpViaEmail;
use Bhry98\LaravelStarterKit\Http\Services\core\enums\CoreEnumsService;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsCitiesService;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsCountriesService;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsGovernoratesService;
use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Bhry98\LaravelStarterKit\Models\users\UsersVerifyCodesModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UsersAuthenticationService
{
    public function createNewVerifyCode(UsersCoreUsersModel $user): UsersVerifyCodesModel|null
    {
        // close all last valid otp
        UsersVerifyCodesModel::query()->where(["user_id" => $user?->id,])->update(["valid" => false]);
        // create new valid otp
        $record = UsersVerifyCodesModel::query()->create([
            "verify_code" => rand(123987, 998756),
            "user_id" => $user?->id,
            "valid" => true,
            "expired_at" => now(config(key: 'app.timezone'))->addMinutes(value: 10),
        ]);
        if ($record) {
            // if added successfully add log [info] and return user
            Log::info(message: "User {$user->code} request a new verify code successfully with id {$record->id}", context: ['user' => $user, 'record' => $record]);
            return $record;
        } else {
            // if added successfully add log [error] and return user
            Log::error(message: "User {$user->code} request a new verify code field");
            return null;
        }
    }

    function registration(array $data)
    {
        // check if user type exists
        $userTypeId = CoreEnumsService::getByCode(UUID: bhry98_app_settings(key: 'default_user_type_in_registration') ?? $data['user_type'])?->id;
        // if normal user type not found return null
        throw_if(!$userTypeId, "No user type found");
        // add normal user in database
        $data['type_id'] = $userTypeId;
        $data['country_id'] = CoreLocationsCountriesService::getDetailsByCode($data['country_id'] ?? '')?->id;
        $data['governorate_id'] = CoreLocationsGovernoratesService::getDetailsByCode($data['governorate_id'] ?? '')?->id;
        $data['city_id'] = CoreLocationsCitiesService::getDetailsByCode($data['city_id'] ?? '')?->id;
        $data['gender_id'] = CoreEnumsService::getByCode($data['gender_id'])?->id;
        $user = UsersCoreUsersModel::query()->create($data);
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
        $user = UsersCoreUsersModel::query()->where('code', $authUser?->code);
        if ($user && $relations) {
            $user->with($relations);
        }
        return $user->first();
    }

    public function logout(): bool
    {
        Log::info("User logged out successfully", ['user' => Auth::user()]);
        Auth::user()?->currentAccessToken()->delete();
        Auth::forgetUser();
        return !auth()->check();
    }

    public function sendForgotPasswordOtpViaEmail(string $email): bool
    {
        // get user via email
        $user = UsersCoreUsersModel::query()->where(["email" => $email])->first();
        // create otp code
        $record = self::createNewVerifyCode($user);
        if ($record) {
            // send otp via email
            Mail::mailer("smtp")->to($user->email)->send(new SendResetPasswordOtpViaEmail($record));
            Log::info("Otp sent to user successfully via email with user code {$user?->code}", ['user' => $user, 'otp' => $record]);
            return true;
        } else {
            Log::error("Otp sent to user failed via email with user code {$user?->code}", ['user' => $user]);
            return false;
        }
    }

    public function verifyOtpIfValidForUser(string $search, int $otp): bool
    {
        $forgotPasswordWay = bhry98_app_settings(key: "default_user_forgot_password_way") ?? "email";
        // get user by forgot password way
        $user = UsersCoreUsersModel::query()->where([$forgotPasswordWay => $search])->first();
        // get verify code by otp and user id
        $otp = UsersVerifyCodesModel::query()->where(["user_id" => $user?->id, "verify_code" => $otp])->first();
        // if dont have user or otp
        if (!$user || !$otp) return false;
        // if otp used before
        if (!$otp->valid) return false;
        // if otp expired
        if (Carbon::parse($otp?->expired_at)->isPast()) return false;
        return true;
    }

    public function generateTokenForResetPassword(int $otp): string
    {
        // get verify code record
        $otp = UsersVerifyCodesModel::query()->where(["verify_code" => $otp])->first();
        // set user must change password
        $otp->user->update(['must_change_password' => true]);
        $otp->update(["valid" => false]);
        return self::loginViaUser($otp->user);
    }

    public function updatePassword(string $newPassword): bool
    {
        $user = self::getAuthUser();
        $user->password = $newPassword;
        $user->must_change_password = false;
        if ($saved = $user->save()) {
            Log::info("User password updated successfully", ['user' => $user]);
        } else {
            Log::error("User password update failed", ['user' => $user]);
        }
        return $saved;
    }
}