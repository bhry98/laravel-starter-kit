<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Bhry98\LaravelStarterKit\Models\core\locations\{
    CoreLocationsCountriesModel,
    CoreLocationsGovernoratesModel
};
return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: CoreLocationsGovernoratesModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->uuid(column: 'code')->index()->unique();
                $table->string(column: 'name', length: 50);
                $table->string(column: 'local_name', length: 50);
                $table->foreignId(column: 'country_id')
                    ->references(column: 'id')
                    ->on(table: CoreLocationsCountriesModel::TABLE_NAME)
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->softDeletes();
                $table->timestamps();
            });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: CoreLocationsGovernoratesModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
