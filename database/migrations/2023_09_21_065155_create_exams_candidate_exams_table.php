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
        Schema::create('exams_candidate_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('exams_id')->unsigned();
            $table->unsignedBiginteger('candidate_exams_id')->unsigned();

            $table->foreign('exams_id')->references('id')
                ->on('exams')->onDelete('cascade');
            $table->foreign('candidate_exams_id')->references('id')
                ->on('candidate_exams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams_candidate_exams');
    }
};
