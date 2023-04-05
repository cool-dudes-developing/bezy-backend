<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('connected_ports', function (Blueprint $table) {
            $table->uuid('id');

            $table->foreignUuid('method_block_id');
            $table->foreignUuid('port_id');
            $table->foreignUuid('connected_to');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connected_ports');
    }
};
