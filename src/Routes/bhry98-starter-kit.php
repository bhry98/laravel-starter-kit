<?php

use Bhry98\LaravelStarterKit\Http\Controllers\core\locations\{
    CoreLocationsCountriesController,
    CoreLocationsGovernoratesController,
    CoreLocationsCitiesController,
};
use Bhry98\LaravelStarterKit\Http\Controllers\core\enums\{
    CoreEnumsController
};
use Bhry98\LaravelStarterKit\Http\Controllers\users\{
    UsersAuthenticationController
};
use Illuminate\Support\Facades\Route;

Route::prefix(config(key: 'bhry98-starter.apis.prefix', default: "api"))
    ->middleware(config(key: 'bhry98-starter.apis.middleware', default: ["api", \Bhry98\LaravelStarterKit\Http\Middlewares\GlobalResponseLocale::class]))
    ->name(config(key: 'bhry98-starter.apis.namespace', default: "api") . ".")
    ->group(function () {
        // helpers
        Route::prefix(config(key: 'bhry98-starter.apis.helpers.prefix', default: "helpers"))
            ->middleware(config(key: 'bhry98-starter.apis.helpers.middleware', default: ["api"]))
            ->name(config(key: 'bhry98-starter.apis.helpers.namespace', default: "helpers") . ".")
            ->group(function () {
                // locations
                Route::name("locations.")
                    ->prefix("locations")
                    ->group(function () {
                        Route::name("countries.")
                            ->prefix("countries")
                            ->group(function () {
                                Route::get('/', [CoreLocationsCountriesController::class, 'getAllWithPagination'])
                                    ->name(name: 'get-all-with-pagination'); // without auth
                                Route::get('/{country_code}', [CoreLocationsCountriesController::class, 'getDetails'])
                                    ->name(name: 'get-details'); // without auth
                                Route::get('/{country_code}/governorates', [CoreLocationsCountriesController::class, 'getAllGovernorates'])
                                    ->name(name: 'get-all-governorates'); // without auth
                                Route::get('/{country_code}/cities', [CoreLocationsCountriesController::class, 'getAllCities'])
                                    ->name(name: 'get-all-cities'); // without auth
                            });
                        Route::name("governorates.")
                            ->prefix("governorates")
                            ->group(function () {
                                Route::get('/', [CoreLocationsGovernoratesController::class, 'getAllWithPagination'])
                                    ->name(name: 'get-all-with-pagination'); // without auth
                                Route::get('/{governorate_code}', [CoreLocationsGovernoratesController::class, 'getDetails'])
                                    ->name(name: 'get-details'); // without auth
                                Route::get('/{governorate_code}/cities', [CoreLocationsGovernoratesController::class, 'getAllCities'])
                                    ->name(name: 'get-all-cities'); // without auth
                            });
                        Route::name("cities.")
                            ->prefix("cities")
                            ->group(function () {
                                Route::get('/', [CoreLocationsCitiesController::class, 'getAllWithPagination'])
                                    ->name(name: 'get-all-with-pagination'); // without auth
                                Route::get('/{city_code}', [CoreLocationsCitiesController::class, 'getDetails'])
                                    ->name(name: 'get-details'); // without auth
                            });
                    });
                // enums
                Route::name("enums.")
                    ->prefix("enums")
                    ->group(function () {
                        Route::get('/{enum_type}', [CoreEnumsController::class, 'getAllWithPaginationByRelation'])
                            ->name(name: 'all-enums-by-relation'); // without auth
                    });
            });
        // auth routes
        Route::prefix('auth')
            ->name('auth.')
            ->group(function () {
                Route::post('/registration', [UsersAuthenticationController::class, 'registration'])
                    ->name(name: 'registration'); // without auth
                Route::post('/login', [UsersAuthenticationController::class, 'login'])
                    ->name(name: 'login'); // without auth
            });
    });