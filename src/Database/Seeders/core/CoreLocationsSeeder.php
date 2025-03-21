<?php

namespace Bhry98\LaravelStarterKit\Database\Seeders\core;

use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCitiesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsCountriesModel;
use Bhry98\LaravelStarterKit\Models\core\locations\CoreLocationsGovernoratesModel;
use Illuminate\Database\Seeder;

class CoreLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $countriesArray = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}countries.php";
        foreach ($countriesArray ?? [] as $country) {
            $fixData = [
                "country_code" => $country["code"],
                "name" => $country["name"],
                "local_name" => $country["local_name"],
                "flag" => $country["flag"],
                "lang_key" => $country["lang_key"],
                "system_lang" => false,
            ];
            $countryAfterAdd = CoreLocationsCountriesModel::create($fixData);
            if ($countryAfterAdd) {
                match ($country["code"]) {
                    "EG" => self::addEgyptGovernorates($countryAfterAdd?->id),
                    "SA" => self::addSaudiArabiaGovernorates($countryAfterAdd?->id),
                    default => null,
                };
            }
        }
    }

    /**
     * @param $egypt_id
     * @return void
     * to add all egypt governorates and call add cities method for each governorate
     */
    static function addEgyptGovernorates($egypt_id): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $egyptGovernoratesArray = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}governorates{$ds}egypt.php";
        foreach ($egyptGovernoratesArray ?? [] as $governorate) {
            $fixData = [
                "name" => $governorate["name"],
                "local_name" => $governorate["local_name"],
                "country_id" => $egypt_id
            ];
            $governorateAfterAdd = CoreLocationsGovernoratesModel::create($fixData);
            if ($governorateAfterAdd) {
                match ($governorate["name"]) {
                    "Cairo" => self::addEgyptCairoCities($egypt_id, $governorateAfterAdd?->id),
                    "Giza" => self::addEgyptGizaCities($egypt_id, $governorateAfterAdd?->id),
                    "Alexandria" => self::addEgyptAlexandriaCities($egypt_id, $governorateAfterAdd?->id),
                    default => null,
                };
            }
        }
    }

    /**
     * @param $egypt_id
     * @param $cairo_id
     * @return void
     * to add all cities in cairo
     */
    static function addEgyptCairoCities($egypt_id, $cairo_id): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $egyptCairoCitiesArray = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}cities{$ds}egypt{$ds}cairo.php";
        foreach ($egyptCairoCitiesArray ?? [] as $city) {
            $fixData = [
                "name" => $city["name"],
                "local_name" => $city["local_name"],
                "country_id" => $egypt_id,
                "governorate_id" => $cairo_id
            ];
            CoreLocationsCitiesModel::create($fixData);
        }
    }

    /**
     * @param $egypt_id
     * @param $giza_id
     * @return void
     * to add all cities in giza
     */
    static function addEgyptGizaCities($egypt_id, $giza_id): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $egyptCairoCitiesArray = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}cities{$ds}egypt{$ds}giza.php";
        foreach ($egyptCairoCitiesArray ?? [] as $city) {
            $fixData = [
                "name" => $city["name"],
                "local_name" => $city["local_name"],
                "country_id" => $egypt_id,
                "governorate_id" => $giza_id
            ];
            CoreLocationsCitiesModel::create($fixData);
        }
    }

    /**
     * @param $egypt_id
     * @param $alexandria_id
     * @return void
     * to add all cities in alexandria
     */
    static function addEgyptAlexandriaCities($egypt_id, $alexandria_id): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $egyptCairoCitiesArray = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}cities{$ds}egypt{$ds}alexandria.php";
        foreach ($egyptCairoCitiesArray ?? [] as $city) {
            $fixData = [
                "name" => $city["name"],
                "local_name" => $city["local_name"],
                "country_id" => $egypt_id,
                "governorate_id" => $alexandria_id
            ];
            CoreLocationsCitiesModel::create($fixData);
        }
    }

    static function addSaudiArabiaGovernorates($saudi_arabia_id): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $egyptGovernoratesArray = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}governorates{$ds}saudi_arabia.php";
        foreach ($egyptGovernoratesArray ?? [] as $governorate) {
            $fixData = [
                "name" => $governorate["name"],
                "local_name" => $governorate["local_name"],
                "country_id" => $saudi_arabia_id
            ];
            CoreLocationsGovernoratesModel::create($fixData);
        }
    }
}