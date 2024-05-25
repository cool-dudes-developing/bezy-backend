<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('published_assets', function (Blueprint $table) {
            $table->foreignUuid('block_id')->constrained()->cascadeOnDelete();
            $table->string('caption')->nullable();
            $table->dropColumn('downloads');
        });
    }

    public function down(): void
    {
        Schema::table('published_assets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('block_id');
            $table->dropColumn('caption');
            $table->integer('downloads')->default(0);
        });
    }
};
