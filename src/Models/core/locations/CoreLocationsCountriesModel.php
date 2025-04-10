<?php

namespace Bhry98\LaravelStarterKit\Models\core\locations;

use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CoreLocationsCountriesModel extends Model
{
    use SoftDeletes;
//
//    protected static array $localizable = [
//        'name'
//    ];
    // start env
    const TABLE_NAME = "core_locations_countries";
    const RELATIONS = [];
    // start table
    protected $table = self::TABLE_NAME;
    public $timestamps = true;
    protected $fillable = [
        "code",
        "country_code",
        "name",
        "local_name",
        "flag",
        "lang_key",
        "system_lang",
    ];
    protected $casts = [
        "name" => "string",
        "code" => "string",
        "system_lang" => "boolean",
    ];

    public function governorates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            related: CoreLocationsGovernoratesModel::class,
            foreignKey: "country_id",
            localKey: "id");
    }

    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            related: CoreLocationsCitiesModel::class,
            foreignKey: "country_id",
            localKey: "id");
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            related: UsersCoreUsersModel::class,
            foreignKey: "country_id",
            localKey: "id");
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

}
