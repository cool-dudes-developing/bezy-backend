<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('method_blocks', function (Blueprint $table) {
            $table->string('constant')->nullable()->after('y');
        });
    }

    public function down(): void
    {
        Schema::table('method_blocks', function (Blueprint $table) {
            $table->dropColumn('constant');
        });
    }
};
