<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('block_id')->comment('The block that this connection belongs to');
            $table->foreignUuid('from_method_block_id')->comment('The method block that this connection starts from');
            $table->foreignUuid('to_method_block_id')->comment('The method block that this connection ends at');
            $table->foreignUuid('from_port_id')->comment('The port that this connection starts from');
            $table->foreignUuid('to_port_id')->comment('The port that this connection ends at');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connected_ports');
    }
};
