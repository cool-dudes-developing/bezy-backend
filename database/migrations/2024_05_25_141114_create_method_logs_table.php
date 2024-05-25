<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('method_logs', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('project_id');
            $table->foreignUuid('block_id');
            $table->string('run_id');
            $table->string('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('method_logs');
    }
};
