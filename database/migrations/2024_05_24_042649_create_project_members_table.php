<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->foreignUuid('project_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->primary(['project_id', 'user_id']);
            $table->string('role', 20)->default('member');
            $table->timestamp('accepted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
