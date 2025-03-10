<?php

use Bhry98\LaravelUsersCore\Models\{
    UsersExtraColumnsModel,
    UsersCoreUsersModel,
    UsersExtraValuesModel
};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: UsersExtraValuesModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->foreignId(column: 'column_id')
                    ->references(column: 'id')
                    ->on(table: UsersExtraColumnsModel::TABLE_NAME)
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->foreignId(column: 'core_user_id')
                    ->references(column: 'id')
                    ->on(table: UsersCoreUsersModel::TABLE_NAME)
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->string(column: 'value');
            });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: UsersExtraValuesModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
