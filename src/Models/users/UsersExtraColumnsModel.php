<?php

namespace Bhry98\LaravelStarterKit\Models\users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UsersExtraColumnsModel extends Model
{
    use SoftDeletes;

    // start env
    const TABLE_NAME = "users_extra_columns";
    // start table
    protected $table = self::TABLE_NAME;
    protected $fillable = [
        "code",
        "type_id",
        "default_input_name",
        "input_names",
        "validation"
    ];
    protected $casts = [
        "input_names" => "array",
        "validation" => "array",

    ];

    public function Type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(
            related: UsersCoreTypesModel::class,
            foreignKey: "id",
            localKey: "type_id"
        );
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
