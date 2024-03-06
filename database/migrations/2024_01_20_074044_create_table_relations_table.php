<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('table_relations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->enum('type', ['one-to-one', 'one-to-many', 'many-to-many', 'one-to-many-nullable']);
            $table->foreignUuid('source_table_id')->constrained('project_tables')->cascadeOnDelete();
            $table->foreignUuid('target_table_id')->constrained('project_tables')->cascadeOnDelete();
            $table->foreignUuid('source_column_id')->constrained('table_columns')->cascadeOnDelete();
            $table->foreignUuid('target_column_id')->constrained('table_columns')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_relations');
    }
};
