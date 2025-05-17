<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fitbit_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // Assuming 1 token per user
            $table->string('access_token');
            $table->string('refresh_token');
            $table->string('scope')->nullable();
            $table->integer('expires_in')->nullable();
            $table->timestamp('expires_at')->nullable(); // Helps with auto-refresh logic
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fitbit_tokens');
    }
};
