<?php

namespace Bhry98\LaravelStarterKit\Models\core\sessions;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

class CoreSessionsPersonalAccessTokenModel extends PersonalAccessToken
{
    // start env
    const TABLE_NAME = "core_sessions_personal_access_tokens";
    // start table
    protected $table = self::TABLE_NAME;
}