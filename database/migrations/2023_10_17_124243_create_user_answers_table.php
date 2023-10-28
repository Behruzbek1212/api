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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exam_user_id')->unsigned()->nullable();
            $table->bigInteger('questions_for_exam_id')->unsigned()->nullable();
            $table->bigInteger('answer_variant_id')->unsigned()->nullable();
            $table->integer('score')->nullable()->comment('javob uchun belgilangan ball');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_answers');
    }
};
