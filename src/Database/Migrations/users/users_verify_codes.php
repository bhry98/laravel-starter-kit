<?php

use Bhry98\LaravelStarterKit\Models\users\{
    UsersCoreUsersModel,
    UsersVerifyCodesModel
};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: UsersVerifyCodesModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->integer(column: 'verify_code');
                $table->foreignId(column: 'user_id')
                    ->references(column: 'id')
                    ->on(table: UsersCoreUsersModel::TABLE_NAME)
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->timestamp(column: 'expired_at')
                    ->nullable();
                $table->boolean(column: 'valid')->default(value: true);
                $table->timestamp(column: 'created_at')->useCurrent();
                $table->timestamp(column: 'updated_at')->useCurrentOnUpdate();
            });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: UsersVerifyCodesModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
