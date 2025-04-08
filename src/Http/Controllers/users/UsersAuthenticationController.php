<?php

namespace Bhry98\LaravelStarterKit\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Bhry98\LaravelStarterKit\Http\Resources\users\UserResource;
use Bhry98\LaravelStarterKit\Http\Services\users\UsersAuthenticationService;
use Illuminate\Support\Facades\DB;
use Bhry98\LaravelStarterKit\Http\Requests\users\authentication\{UsersForgotPasswordRequest,
    UsersLoginRequest,
    UsersRegistrationRequest,
    UsersUpdatePasswordRequest,
    UsersVerifyOtpRequest
};

class UsersAuthenticationController extends Controller
{
    function registration(UsersRegistrationRequest $request, UsersAuthenticationService $authenticationService): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $newUserData = $authenticationService->registration($request->validated());
            if ($newUserData) {
                DB::commit();
                if (bhry98_app_settings(key: "auto_login_after_registration")) {
                    return bhry98_response_success_with_data([
                        'access_type' => 'Bearer',
                        'access_token' => $authenticationService->loginViaUser($newUserData),
                        "user" => UserResource::make($authenticationService->getAuthUser()),
                    ],
                        __(key: "bhry98::users.registration-success"));
                } else {
                    return bhry98_response_success_with_data([], __(key: "bhry98::users.registration-success-plz-login"));
                }
            } else {
                DB::rollBack();
                return bhry98_response_success_without_data();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

    function login(UsersLoginRequest $request, UsersAuthenticationService $authenticationService): \Illuminate\Http\JsonResponse
    {
        try {
            $loginWay = bhry98_app_settings(key: "default_user_login_way");
            $token = match ($loginWay) {
                "email" => $authenticationService->loginViaEmailAndPassword($request->validated()),
                "national_id" => $authenticationService->loginViaNationalIDAndPassword($request->validated()),
                "phone_number" => $authenticationService->loginViaPhoneAndPassword($request->validated()),
                default => $authenticationService->loginViaUsernameAndPassword($request->validated()),
            };
            if (is_null($token)) return bhry98_response_validation_error([
                'password' => __(key: 'validation.exists', replace: ["attribute" => "password"])],
                __(key: "bhry98::responses.login-failed"));
            return bhry98_response_success_with_data([
                'access_type' => 'Bearer',
                'access_token' => $token,
                "user" => UserResource::make($authenticationService->getAuthUser()),
            ]);
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

    function forgotPassword(UsersForgotPasswordRequest $request, UsersAuthenticationService $authenticationService): \Illuminate\Http\JsonResponse
    {
        try {
            $forgotPasswordWay = bhry98_app_settings(key: "default_user_forgot_password_way");
            $otpHasSend = match ($forgotPasswordWay) {
                "phone_number" => null,
                default => $authenticationService->sendForgotPasswordOtpViaEmail($request->get('email')),
            };
            if ($otpHasSend) {
                return bhry98_response_success_with_data(message: __("bhry98::users.forgot-password-via-email-success-plz-check"));
            } else {
                return bhry98_response_validation_error(message: __("bhry98::users.forgot-password-via-email-fail"));
            }
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

    function verifyOtp(UsersVerifyOtpRequest $request, UsersAuthenticationService $authenticationService): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            // check if user valid for this otp
            $searchKey = bhry98_app_settings(key: "default_user_forgot_password_way") ?? "email";
            if ($authenticationService->verifyOtpIfValidForUser($request->get($searchKey), $request->get(key: "otp"))) {
                // generate token for user by login and set must change password to true
                $token = $authenticationService->generateTokenForResetPassword($request->get(key: "otp"));
                DB::commit();
                return bhry98_response_success_with_data(data: ['token' => $token], message: __("bhry98::users.otp-verify-success-plz-reset-password"));
            } else {
                DB::rollBack();
                return bhry98_response_validation_error(message: __("bhry98::users.forgot-password-via-email-fail"));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

    function logout(UsersAuthenticationService $authenticationService): \Illuminate\Http\JsonResponse
    {
        try {
            if ($authenticationService->logout()) {
                return bhry98_response_success_with_data(message: __(key: "bhry98::responses.logout-success"));
            } else {
                return bhry98_response_success_without_data(message: __(key: "bhry98::responses.logout-failed"));
            }
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

    function updatePassword(UsersUpdatePasswordRequest $request, UsersAuthenticationService $authenticationService): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $updatedPassword = $authenticationService->updatePassword($request->get(key: 'password'));
            if ($updatedPassword) {
                DB::commit();
                return bhry98_response_success_with_data(message: __(key: "bhry98::users.update-password-success"));
            } else {
                DB::rollBack();
                return bhry98_response_success_without_data(message: __(key: "bhry98::users.update-password-failed"));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

}
