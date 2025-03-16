<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use  Bhry98\LaravelStarterKit\Models\core\enums\CoreEnumsModel;
return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create(
            table: CoreEnumsModel::TABLE_NAME,
            callback: function (Blueprint $table) {
                $table->id();
                $table->uuid(column: 'code')->unique();
                $table->string(column: 'relation');
                $table->string(column: 'column_name');
                $table->boolean(column: 'api_access')->default(value: false);
                $table->boolean(column: 'can_delete')->default(value: true);
                $table->foreignId(column: 'parent_id')
                    ->nullable()
                    ->references(column: 'id')
                    ->on(table: CoreEnumsModel::TABLE_NAME)
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
                $table->softDeletes();
                $table->timestamps();
            });
        Schema::enableForeignKeyConstraints();
    }
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: CoreEnumsModel::TABLE_NAME);
        Schema::enableForeignKeyConstraints();
    }
};
