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

class UsersManagementService
{

    static public function getMe(array|null $relations = null): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $authUser = Auth::user();
        $user = UsersCoreUsersModel::where('code', $authUser?->code);
        if ($user && $relations) {
            $user->with($relations);
        }
        return $user->first();
    }
}