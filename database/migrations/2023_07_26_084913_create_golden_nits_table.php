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
        Schema::create('golden_nits', function (Blueprint $table) {
            $table->id();
            $table->string('name_surname');
            $table->string('phone')->unique()->nullable();
            $table->string('seniority')->nullable();
            $table->integer('telegram_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golden_nits');
    }
};
