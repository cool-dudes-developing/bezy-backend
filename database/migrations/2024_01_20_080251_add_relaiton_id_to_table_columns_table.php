<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('table_columns', function (Blueprint $table) {
            $table->foreignUuid('relation_id')->after('table_id')->nullable()->constrained('table_relations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('table_columns', function (Blueprint $table) {
            $table->dropForeign(['relation_id']);
            $table->dropColumn('relation_id');
        });
    }
};
