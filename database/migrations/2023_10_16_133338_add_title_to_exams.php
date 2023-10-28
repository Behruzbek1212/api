<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->text('title')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('datetime_start')->nullable();
            $table->dateTime('datetime_end')->nullable();
            $table->integer('max_ball')->nullable();
            $table->integer('attemps_count')->default(0);
            $table->integer('duration')->nullable();
            $table->integer('questions_count')->nullable();
            $table->boolean('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('image');
            $table->dropColumn('datetime_start');
            $table->dropColumn('datetime_end');
            $table->dropColumn('max_ball');
            $table->dropColumn('attemps_count');
            $table->dropColumn('duration');
            $table->dropColumn('questions_count');
            $table->dropColumn('status');
        });
    }
};
