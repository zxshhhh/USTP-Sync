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
            $table->unsignedBigInteger('user_id')->index();
            $table->string('access_token', 1000);
            $table->string('refresh_token', 1000);
            $table->text('scope')->nullable();
            $table->integer('expires_in')->nullable(); // seconds until expiration
            $table->timestamp('expires_at')->nullable(); // exact expiration time
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
