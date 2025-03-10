<?php

namespace Bhry98\LaravelStarterKit\Models\users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UsersVerifyCodesModel extends Model
{

    // start env
    const TABLE_NAME = "users_verify_codes";
    const RELATIONS = ["user"];
    // start table
    protected $table = self::TABLE_NAME;
    protected $fillable = [
        "verify_code",
        "user_id",
        "valid",
        "expired_at",
        "created_at",
        "updated_at",
    ];
    protected $casts = [
        "valid" => "boolean",
        "verify_code" => "integer",
        "expired_at" => "datetime",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: UsersCoreUsersModel::class,
            foreignKey: "id",
            localKey: "user_id");
    }
}
