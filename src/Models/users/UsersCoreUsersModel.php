<?php

namespace Bhry98\LaravelStarterKit\Models\users;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authentication;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class UsersCoreUsersModel extends Authentication
{
    use HasApiTokens, SoftDeletes;

    // start env
    const TABLE_NAME = "users_core_users";
    const RELATIONS = ["country","governorate","city","type","gender"];
    // start table
    protected $table = self::TABLE_NAME;
    protected $fillable = [
        "code",
        "type_id",
        "country_id",
        "governorate_id",
        "city_id",
        "gender_id",
        "display_name",
        "first_name",
        "last_name",
        "phone_number",
        "national_id",
        "birthdate",
        "username",
        "email",
        "email_verified_at",
        "must_change_password",
        "password",
    ];
    protected $casts = [
        "email_verified_at" => "datetime",
        "password" => "hashed",
        "remember_token" => "string",
        "must_change_password" => "boolean",
        "birthdate" => "date:Y-m-d",
        "national_id" => "integer",
        "created_at" => "datetime",
        "updated_at" => "datetime",
        "deleted_at" => "datetime",
    ];

    public function extraColumns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            related: UsersExtraValuesModel::class,
            foreignKey: "core_user_id",
            localKey: "id");
    }

    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: CoreEnumsModel::class,
            foreignKey: "id",
            localKey: "type_id");
    }
    public function gender(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: CoreEnums::class,
            foreignKey: "id",
            localKey: "gender_id");
    }
    public function country(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: CoreLocationsCountriesModel::class,
            foreignKey: "id",
            localKey: "country_id");
    }
    public function governorate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: CoreLocationsGovernoratesModel::class,
            foreignKey: "id",
            localKey: "governorate_id");
    }
    public function city(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: CoreLocationsGovernoratesModel::class,
            foreignKey: "id",
            localKey: "city_id");
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            // create new unique code
            $model->code = self::generateNewCode();
        });
    }
    static function generateNewCode(): string
    {
        $code = Str::uuid();
        if (static::where('code', $code)->exists()) {
            return self::generateNewCode();
        }
        return $code;
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *users
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
