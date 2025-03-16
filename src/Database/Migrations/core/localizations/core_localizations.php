<?php

use Bhry98\LaravelStarterKit\Models\core\localizations\{
    CoreLocalizationsModel
};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: CoreLocalizationsModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->string(column: 'relation');
                $table->string(column: 'column_name');
                $table->string(column: 'locale');
                $table->string(column: 'value');
                $table->string(column: 'reference_id');
                $table->softDeletes();
                $table->timestamps();
            });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: CoreLocalizationsModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
