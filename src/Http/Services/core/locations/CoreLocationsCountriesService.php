<?php

namespace Bhry98\LaravelStarterKit\Http\Services\core\locations;

use Bhry98\LaravelStarterKit\Models\core\{locations\CoreLocationsCitiesModel,
    locations\CoreLocationsCountriesModel,
    locations\CoreLocationsGovernoratesModel
};

class CoreLocationsCountriesService
{
    static function getAllWithPagination(int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null)
    {
        $data = CoreLocationsCountriesModel::withCount('users', 'cities', 'governorates');
        if (!empty($searchForWord)) {
             $data->where('name', 'like', '%' . $searchForWord . '%')
                ->orWhere('local_name', 'like', '%' . $searchForWord . '%');
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

    static function getDetailsByCode($countryCode, array|null $relations = null)
    {
        $data = CoreLocationsCountriesModel::where('code', $countryCode)
            ->withCount('users', 'cities', 'governorates');
        if (!is_null($relations)) {
            $data->with($relations);
        }
        return $data->first();
    }

    static function getAllGovernoratesInCountryWithPagination(string $country_uuid, int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null)
    {
        $country = CoreLocationsCountriesModel::where('code', $country_uuid)->first();
        $data = CoreLocationsGovernoratesModel::where('country_id', $country->id)->withCount('users', 'cities');
        if (!empty($searchForWord)) {
            //            $data->whereHas('posts', function ($query) {
            //                $query->where('title', 'like', '%Laravel%');
            //            });
            $data->where('name', 'like', '%' . $searchForWord . '%')
                ->orWhere('local_name', 'like', '%' . $searchForWord . '%');
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

    static function getAllCitiesInCountryWithPagination(string $country_uuid, int $pageNumber = 0, int $perPage = 10, string|null $searchForWord = null, array|null $relations = null)
    {
        $country = CoreLocationsCountriesModel::where('code', $country_uuid)->first();
        $data = CoreLocationsCitiesModel::where('country_id', $country->id)->withCount('users');
        if (!empty($searchForWord)) {
            //            $data->whereHas('posts', function ($query) {
            //                $query->where('title', 'like', '%Laravel%');
            //            });
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
