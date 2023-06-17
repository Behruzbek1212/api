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
        Schema::create('trafics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->string('name')->nullable()->comment('nomi');
            $table->float('price')->nullable()->comment('narxi');
            $table->string('title')->nullable()->comment('sarlavha');
            $table->text('description')->nullable();
            $table->string('top_day')->nullable()->comment('topda turish muddati');
            $table->string('count_rise')->nullable()->comment('nechi marta kutarilishi');
            $table->string('vip_day')->nullable()->comment('vip elonlarda qancha turishi');
            $table->string('type')->nullable()->comment('type standart,vip');
            $table->boolean('status')->nullable()->comment('holati');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trafics');
    }
};
