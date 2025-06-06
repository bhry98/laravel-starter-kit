<?php


namespace Bhry98\LaravelStarterKit\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class HandlerUnAuthenticatedException extends ExceptionHandler
{
    /**
     * Render an unauthenticated response.
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        return bhry98_response_unauthenticated();
    }

    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
