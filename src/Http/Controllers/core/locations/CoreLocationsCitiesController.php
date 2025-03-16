<?php

namespace Bhry98\LaravelStarterKit\Http\Controllers\core\locations;

use App\Http\Controllers\Controller;
use Bhry98\LaravelStarterKit\Http\Requests\core\locations\cities\{GetAllCitiesRequest, GetCityDetailsRequest};
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CityResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\GovernorateResource;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsCitiesService;

class CoreLocationsCitiesController extends Controller
{
    function getAllWithPagination(GetAllCitiesRequest $request, CoreLocationsCitiesService $citiesService): \Illuminate\Http\JsonResponse
    {
        try {
            $citiesData = $citiesService->getAllWithPagination(
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

    function getDetails(GetCityDetailsRequest $request, CoreLocationsCitiesService $citiesService): \Illuminate\Http\JsonResponse
    {
        try {
            $cityData = $citiesService->getDetailsByCode(
                $request->get(key: 'city_code'),
                $request->get(key: 'with'),
            );
            if ($cityData) {
                return bhry98_response_success_with_data(CityResource::make($cityData));
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
