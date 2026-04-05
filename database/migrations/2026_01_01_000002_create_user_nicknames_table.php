<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_nicknames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_id')->constrained('users')->cascadeOnDelete();
            $table->string('nickname');
            $table->timestamps();
            $table->unique(['setter_id', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_nicknames');
    }
};
