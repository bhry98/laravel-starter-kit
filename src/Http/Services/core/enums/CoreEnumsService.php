<?php

namespace Bhry98\LaravelStarterKit\Http\Services\core\enums;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;

class CoreEnumsService
{
    static function getAllWithPaginationByRelationAndColumn(string $type, int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null, bool $only_has_api_access = true)
    {
        $table = CoreEnumsModel::ENUMS_MODELS[$type]['table'];
        $column = CoreEnumsModel::ENUMS_MODELS[$type]['column'];
        $data = CoreEnumsModel::where(['column_name' => $column, 'relation' => $table]);
        if ($only_has_api_access) {
            $data->where('api_access', true);
        }
        if (!empty($searchForWord)) {
            $data->whereHas('translations', function ($query) use ($searchForWord) {
                $query->where('value', 'like', "%$searchForWord%");
            });
            $pageNumber = 0;
        }
        return $data->paginate(
            columns: [
                "*"
            ],
            perPage: $perPage,
            page: $pageNumber,
        );
    }

    static function getByCode(string $UUID, bool $only_has_api_access = true, array|null $relations = [])
    {
        $data = CoreEnumsModel::where('code', $UUID);
        if ($only_has_api_access) {
            $data->where('api_access', true);
        }
        if (!is_null($relations)) {
            $data->with($relations);
        }
        return $data->first();
    }
}
