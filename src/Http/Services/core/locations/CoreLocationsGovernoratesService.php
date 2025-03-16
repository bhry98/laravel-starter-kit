<?php

namespace Bhry98\LaravelStarterKit\Http\Services\core\locations;

use Bhry98\LaravelStarterKit\Models\core\{locations\CoreLocationsCitiesModel,
    locations\CoreLocationsGovernoratesModel,};

class CoreLocationsGovernoratesService
{
    static function getAllWithPagination( int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null, array|null $relations = null)
    {
        $data = CoreLocationsGovernoratesModel::withCount('users', 'cities');
        if (!empty($searchForWord)) {
            $data->where('name', 'like', '%' . $searchForWord . '%')
                ->orWhere('local_name', 'like', '%' . $searchForWord . '%');
            $pageNumber = 0;
        }
        if (!empty($relations)) {
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

    static function getDetailsByCode($governorateCode, array|null $relations = null)
    {
        $data = CoreLocationsGovernoratesModel::where('code', $governorateCode)
            ->withCount('users', 'cities');
        if (!is_null($relations)) {
            $data->with($relations);
        }
        return $data->first();
    }

    static function getAllCitiesInGovernorateWithPagination(string $governorate_uuid, int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null, array|null $relations = null)
    {
        $governorate = CoreLocationsGovernoratesModel::where('code', $governorate_uuid)->first();
        $data = CoreLocationsCitiesModel::where('governorate_id', $governorate->id)->withCount('users');
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
}
