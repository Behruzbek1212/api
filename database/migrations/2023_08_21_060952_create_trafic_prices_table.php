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
        Schema::create('trafic_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('count')->nullable()->comment('elonlar soni');
            $table->double('price', 15, 5)->default(0)->comment('elonlar narxi');
            $table->float('discount')->nullable()->comment('elonlar chegirmasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trafic_prices');
    }
};
