<?php

namespace Bhry98\LaravelStarterKit\Database\Seeders\core;

use Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
use Illuminate\Database\Seeder;

class CoreDefaultEnumsSeeder extends Seeder
{
    public function run(): void
    {
        self::usersEnums();
    }

    function usersEnums(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $usersEnums = include __DIR__ . "{$ds}..{$ds}..{$ds}Data{$ds}enums{$ds}users.php";
        foreach ($usersEnums ?? [] as $key => $enum) {
            foreach ($enum ?? [] as $enumValue) {
                $enumRecord = CoreEnumsModel::create([
                    'column_name' => $enumValue['column_name'],
                    'relation' => $enumValue['relation'],
                    'api_access' => $enumValue['api_access'],
                    'can_delete' => $enumValue['can_delete'],
                    'parent_id' => $enumValue['parent_id'],
                ]);
                foreach ($enumValue['locales'] ?? [] as $local => $value) {
                    $enumRecord->setLocalized('name', $value, $local);
                }
            }
        }
    }
}