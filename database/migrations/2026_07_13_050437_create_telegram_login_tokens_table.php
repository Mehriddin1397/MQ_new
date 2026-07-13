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
        Schema::create('telegram_login_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_login_tokens');
    }
};
