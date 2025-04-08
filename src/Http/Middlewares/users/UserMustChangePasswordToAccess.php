<?php

namespace Bhry98\LaravelStarterKit\Http\Middlewares\users;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMustChangePasswordToAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->must_change_password){
                return bhry98_response_unauthenticated(message: __(key: "bhry98::users.must-change-password"));
            }
            return $next($request);
        }
        return $next($request);

    }
}
