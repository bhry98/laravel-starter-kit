<?php

namespace Bhry98\LaravelStarterKit\Http\Controllers\core\enums;

use App\Http\Controllers\Controller;
use Bhry98\LaravelStarterKit\Http\Requests\core\enums\GetAllEnumsByRelationAndColumnRequest;
use Bhry98\LaravelStarterKit\Http\Resources\core\enums\EnumsResource;
use Bhry98\LaravelStarterKit\Http\Services\core\enums\CoreEnumsService;
class CoreEnumsController extends Controller
{
    function getAllWithPaginationByRelation(GetAllEnumsByRelationAndColumnRequest $request, CoreEnumsService $enumsService): \Illuminate\Http\JsonResponse
    {
        try {
            $citiesData = $enumsService->getAllWithPaginationByRelationAndColumn(
                $request->get(key: 'enum_type'),
                $request->get(key: 'pageNumber'),
                $request->get(key: 'perPage'),
                $request->get(key: 'searchForWord'),
            );
            if ($citiesData) {
                return bhry98_response_success_with_data(EnumsResource::collection($citiesData)->response()->getData(true));
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
