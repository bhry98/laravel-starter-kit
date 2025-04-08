<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Bhry98\LaravelStarterKit\Models\users\{
    UsersExtraColumnsModel
};
use Bhry98\LaravelStarterKit\Models\core\enums\{
    CoreEnumsModel
};

return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: UsersExtraColumnsModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->uuid(column: 'code')->unique();
                $table->boolean(column: 'required')->default(value: false);
                $table->string(column: 'type')->default(value: 'text');
                $table->json(column: 'validations')->nullable();
                $table->foreignId(column: 'type_id')
                    ->references(column: 'id')
                    ->on(table: CoreEnumsModel::TABLE_NAME)
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
        Schema::dropIfExists(table: UsersExtraColumnsModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
