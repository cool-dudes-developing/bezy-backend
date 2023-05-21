<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_blocks', function (Blueprint $table) {

            $table->foreignUuid('project_id');
            $table->foreignUuid('block_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('methods');
    }
};
