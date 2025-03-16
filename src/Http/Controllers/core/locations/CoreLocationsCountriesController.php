<?php

namespace Bhry98\LaravelStarterKit\Http\Controllers\core\locations;

use App\Http\Controllers\Controller;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CityResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\CountryResource;
use Bhry98\LaravelStarterKit\Http\Resources\core\locations\GovernorateResource;
use Bhry98\LaravelStarterKit\Http\Services\core\locations\CoreLocationsCountriesService;
use Bhry98\LaravelStarterKit\Http\Requests\core\locations\countries\{GetAllCountriesRequest,
    GetAllCountryCitiesRequest,
    GetAllCountryGovernoratesRequest,
    GetCountryDetailsRequest
};

class CoreLocationsCountriesController extends Controller
{
    function getAllWithPagination(GetAllCountriesRequest $request, CoreLocationsCountriesService $countriesService): \Illuminate\Http\JsonResponse
    {
        try {
            $countriesData = $countriesService->getAllWithPagination($request->get(key: 'pageNumber'), $request->get(key: 'perPage'), $request->get(key: 'searchForWord'));
            if ($countriesData) {
                return bhry98_response_success_with_data(CountryResource::collection($countriesData)->response()->getData(true));
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

    function getDetails(GetCountryDetailsRequest $request, CoreLocationsCountriesService $countriesService): \Illuminate\Http\JsonResponse
    {
        try {
            $countryData = $countriesService->getDetailsByCode($request->get(key: 'country_code'));
            if ($countryData) {
                return bhry98_response_success_with_data(CountryResource::make($countryData));
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

    function getAllGovernorates(GetAllCountryGovernoratesRequest $request, CoreLocationsCountriesService $countriesService): \Illuminate\Http\JsonResponse
    {
        try {
            $governoratesData = $countriesService->getAllGovernoratesInCountryWithPagination(
                $request->get(key: 'country_code'),
                $request->get(key: 'pageNumber'),
                $request->get(key: 'perPage'),
                $request->get(key: 'searchForWord'));
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

    function getAllCities(GetAllCountryCitiesRequest $request, CoreLocationsCountriesService $countriesService): \Illuminate\Http\JsonResponse
    {
        try {
            $citiesData = $countriesService->getAllCitiesInCountryWithPagination(
                $request->get(key: 'country_code'),
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
