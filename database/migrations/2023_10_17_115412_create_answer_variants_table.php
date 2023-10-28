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
        Schema::create('answer_variants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('questions_for_exam_id')->unsigned()->nullable();
            $table->string('answer')->nullable()->comment('Javob matni');
            $table->string('image')->nullable()->comment('Rasm');
            $table->integer('score')->nullable()->comment('javob uchun belgilangan ball');
            $table->timestamps();
        });
    }

    /*
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_variants');
    }
};
