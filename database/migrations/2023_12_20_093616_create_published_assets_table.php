<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('published_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('downloads');
            $table->uuid('author_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('published_assets');
    }
};
