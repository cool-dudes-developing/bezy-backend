<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->uuid('id');

            $table->foreignUuid('block_id');
            $table->string('name');
            $table->string('type');
            $table->boolean('direction');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
