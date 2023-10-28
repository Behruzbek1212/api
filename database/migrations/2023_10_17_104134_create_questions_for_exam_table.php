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
        Schema::create('questions_for_exam', function (Blueprint $table) {
            $table->id();
            $table->string('question')->nullable()->comment('Savol matni');
            $table->string('image')->nullable()->comment('Rasm');
            $table->string('video')->nullable()->comment('Rasm');
            $table->integer('position')->nullable()->comment('Tartib | 1 | 2 | 3 | 4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions_for_exam');
    }
};
