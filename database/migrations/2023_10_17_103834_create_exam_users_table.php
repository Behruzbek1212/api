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
        Schema::create('exam_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('exam_id')->unsigned()->nullable();
            $table->integer('attempt')->default(0)->comment('Urinish tartibi // 1-marta, 2-marta ');
            $table->integer('procent')->nullable()->comment('Foizi');
            $table->integer('rating')->nullable()->comment('Baho');
            $table->dateTime('datetime_start')->nullable()->comment('Boshlanish vaqti');
            $table->datetime('datetime_end')->nullable()->comment('Tugash vaqti');
            $table->string('key')->nullable()->comment('start and end');
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_users');
    }
};
