<?php

namespace Bhry98\LaravelStarterKit\Models\core\enums;

use Bhry98\LaravelStarterKit\Models\users\UsersCoreUsersModel;
use Bhry98\LaravelStarterKit\Traits\HasLocalization;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CoreEnumsModel extends Model
{
    use SoftDeletes, HasLocalization;

    protected array $localizable = ['name']; // Columns that should be localized
    const TABLE_NAME = 'core_enums';
    const RELATIONS = [];
    const ENUMS_MODELS = [
        'usersGenders' => [
            'table' => UsersCoreUsersModel::class,
            'column' => 'gender_id',
        ],
        'usersTypes' => [
            'table' => UsersCoreUsersModel::class,
            'column' => 'type_id',
        ],
    ];
    protected $table = self::TABLE_NAME;

    protected $fillable = [
        'code',
        'relation',
        'api_access',
        'can_delete',
        'parent_id'
    ];

    protected $hidden = [];

    protected function casts(): array
    {
        return [
            'api_access' => "boolean",
            'can_delete' => "boolean",
        ];
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

//    protected $with = [
//        'translations'
//    ];
//
//    public function name(): Attribute
//    {
//        return new Attribute(
//            get: fn() => $this->defaultTranslation()->value(column: 'value'),
//        );
//    }
//
//    public function translations(): HasMany
//    {
//        return $this->hasMany(
//            related: CoreTranslation::class,
//            foreignKey: 'reference_id',
//            localKey: 'id');
//    }
//
//    public function defaultTranslation(): HasOne
//    {
//        // Making sure that we always retrieve current locale information
//        return $this->translations()->one()->where(column: 'locale', value: app()->getLocale());
//    }
}
