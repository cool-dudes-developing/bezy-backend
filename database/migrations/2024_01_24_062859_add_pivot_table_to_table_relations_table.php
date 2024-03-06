<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('table_relations', function (Blueprint $table) {
            $table->foreignUuid('pivot_table_id')
                ->nullable()
                ->after('target_table_id')
                ->index('table_relations_pivot_table_id_fk')
                ->references('id')
                ->on('project_tables')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('table_relations', function (Blueprint $table) {
            $table->dropForeign('table_relations_pivot_table_id_fk');
            $table->dropColumn('pivot_table_id');
        });
    }
};
