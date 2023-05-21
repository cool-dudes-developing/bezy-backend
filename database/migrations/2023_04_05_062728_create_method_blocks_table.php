<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('method_blocks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('block_id');
            $table->foreignUuid('parent_id');
            $table->integer('x');
            $table->integer('y');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('method_blocks');
    }
};
