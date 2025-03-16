<?php

namespace Bhry98\LaravelStarterKit\Http\Services\core\locations;


use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;

class CoreLocationsCitiesService
{
    static function getAllWithPagination(int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null, array|null $relations = null)
    {
        $data = CoreLocationsCitiesModel::withCount('users');
        if (!empty($searchForWord)) {
            $data->where('name', 'like', '%' . $searchForWord . '%')
                ->orWhere('local_name', 'like', '%' . $searchForWord . '%');
            $pageNumber = 0;
        }
        if ($relations) {
            $data->with($relations);
        }
        return $data->paginate(
            columns: [
                "*"
            ],
            perPage: $perPage,
            page: $pageNumber,
        );
    }

    static function getDetailsByCode(string $city_uuid, array|null $relations = null)
    {
        $data = CoreLocationsCitiesModel::where('code', $city_uuid)
            ->withCount('users');
        if (!is_null($relations)) {
            $data->with($relations);
        }
        return $data->first();
    }


}
