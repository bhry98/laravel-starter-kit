<?php

namespace Bhry98\LaravelStarterKit\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Bhry98\LaravelStarterKit\Http\Requests\users\me\UsersGetMeRequest;
use Bhry98\LaravelStarterKit\Http\Resources\users\UserResource;
use Bhry98\LaravelStarterKit\Http\Services\users\UsersManagementService;


class UsersManagementController extends Controller
{

    function me(UsersGetMeRequest $request, UsersManagementService $usersServices): \Illuminate\Http\JsonResponse
    {
        try {
            // get user auth profile
            return bhry98_response_success_with_data(data: UserResource::make($usersServices->getMe()));
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

}
