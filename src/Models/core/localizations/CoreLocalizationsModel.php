<?php

namespace Bhry98\LaravelStarterKit\Models\core\localizations;

use Bhry98\LaravelStarterKit\Models\core\enums\{
    CoreEnumsModel
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreLocalizationsModel extends Model
{
    use SoftDeletes;

    const TABLE_NAME = 'core_localizations';
    const RELATIONS = [];
    const TRANSLATION_MODELS = [
        'core_enums' => CoreEnumsModel::class,
    ];

    protected $table = self::TABLE_NAME;
    protected $fillable = [
        'relation',
        'column_name',
        'locale',
        'value',
        'reference_id',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(
            related: $this->relation,
            foreignKey: $this->column_name,
            ownerKey: 'id',
        );
    }
}
