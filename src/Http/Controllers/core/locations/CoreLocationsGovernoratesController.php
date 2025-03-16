<?php

namespace Bhry98\LaravelStarterKit\Http\Controllers\core\locations;

use App\Http\Controllers\Controller;
use Bhry98\LaravelStarterKit\Http\Requests\core\locations\governorates\{GetAllGovernorateCitiesRequest,
    GetAllGovernoratesRequest,
    GetGovernorateDetailsRequest
};
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CityResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\GovernorateResource;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsGovernoratesService;

class CoreLocationsGovernoratesController extends Controller
{
    function getAllWithPagination(GetAllGovernoratesRequest $request, CoreLocationsGovernoratesService $governoratesService): \Illuminate\Http\JsonResponse
    {
        try {
            $governoratesData = $governoratesService->getAllWithPagination(
                $request->get(key: 'pageNumber'),
                $request->get(key: 'perPage'),
                $request->get(key: 'searchForWord'),
                $request->get(key: 'with')
            );
            if ($governoratesData) {
                return bhry98_response_success_with_data(GovernorateResource::collection($governoratesData)->response()->getData(true));
            } else {
                return bhry98_response_success_without_data();
            }
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }
    }

    function getDetails(GetGovernorateDetailsRequest $request, CoreLocationsGovernoratesService $governoratesService): \Illuminate\Http\JsonResponse
    {
        try {
            $countryData = $governoratesService->getDetailsByCode(
                $request->get(key: 'governorate_code'),
                $request->get(key: 'with'),
            );
            if ($countryData) {
                return bhry98_response_success_with_data(GovernorateResource::make($countryData));
            } else {
                return bhry98_response_success_without_data();
            }
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }

    }

    function getAllCities(GetAllGovernorateCitiesRequest $request, CoreLocationsGovernoratesService $governoratesService): \Illuminate\Http\JsonResponse
    {
        try {
            $citiesData = $governoratesService->getAllCitiesInGovernorateWithPagination(
                $request->get(key: 'governorate_code'),
                $request->get(key: 'pageNumber'),
                $request->get(key: 'perPage'),
                $request->get(key: 'searchForWord'),
                $request->get(key: 'with')
            );
            if ($citiesData) {
                return bhry98_response_success_with_data(CityResource::collection($citiesData)->response()->getData(true));
            } else {
                return bhry98_response_success_without_data();
            }
        } catch (\Exception $e) {
            return bhry98_response_internal_error([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ]);
        }

    }
}
