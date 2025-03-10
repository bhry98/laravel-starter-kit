<?php
namespace Bhry98\LaravelStarterKit\Traits;
use Bhry98\LaravelStarterKit\Models\core\localizations\CoreLocalizationsModel;
use Illuminate\Support\Facades\App;

trait HasLocalization
{

    /**
     * Get a localized value for a given column.
     */
    public function getLocalized($column, $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        return CoreLocalizationsModel::where('relation', static::class)
            ->where('column_name', $column)
            ->where('reference_id', $this->id)
            ->where('locale', $locale)
            ->value('value') ?? $this->attributes[$column] ?? null;
    }

    /**
     * Set a localized value for a given column.
     */
    public function setLocalized($column, $value, $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        CoreLocalizationsModel::updateOrCreate(
            [
                'relation' => static::class,
                'column_nuse App\Models\Traits\HasLocalization;
ame' => $column,
                'reference_id' => $this->id,
                'locale' => $locale,
            ],
            ['value' => $value]
        );
    }

    /**
     * Override getAttribute to return localized values automatically.
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->localizable ?? [])) {
            return $this->getLocalized($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Override setAttribute to save localized values automatically.
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->localizable ?? [])) {
            $this->setLocalized($key, $value);
        } else {
            parent::setAttribute($key, $value);
        }
    }

    public function deleteLocalized($column, $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        CoreLocalizationsModel::where('relation', static::class)
            ->where('column_name', $column)
            ->where('reference_id', $this->id)
            ->where('locale', $locale)
            ->delete();
    }

    public function forceDeleteLocalized($column, $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        CoreLocalizationsModel::where('relation', static::class)
            ->where('column_name', $column)
            ->where('reference_id', $this->id)
            ->where('locale', $locale)
            ->forceDelete();
    }
}
