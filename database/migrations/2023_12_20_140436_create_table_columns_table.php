<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('table_columns', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('table_id')->constrained('project_tables')->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->boolean('is_nullable')->default(false);
            $table->string('default')->nullable();
            $table->string('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_columns');
    }
};
