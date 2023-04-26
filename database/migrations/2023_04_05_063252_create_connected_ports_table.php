<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('connected_ports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('method_block_id')->comment('The method block that contains the method that connects the ports.');
            $table->foreignUuid('port_id')->comment('Source port.');
            $table->foreignUuid('connected_to')->comment('Destination port.');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connected_ports');
    }
};
