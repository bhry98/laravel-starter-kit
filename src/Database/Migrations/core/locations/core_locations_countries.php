<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Bhry98\LaravelStarterKit\Models\core\locations\{
    CoreLocationsCountriesModel
};
return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: CoreLocationsCountriesModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->uuid(column: 'code')->index()->unique();
                $table->string(column: 'country_code', length: 20)->index()->unique();
                $table->string(column: 'name');
                $table->string(column: 'local_name');
                $table->string(column: 'flag', length: 10);
                $table->string(column: 'lang_key', length: 10);
                $table->string(column: 'timezone', length: 10)
                    ->nullable();
                $table->boolean(column: 'system_lang')->default(value: false);
                $table->softDeletes();
                $table->timestamps();
            });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: CoreLocationsCountriesModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
