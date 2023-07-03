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
        Schema::create('limits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('day')->nullable()->comment('kun');
            $table->decimal('price', 10, 2)->nullable()->comment('narxi');
            $table->integer('condidate_limit')->nullable()->comment('nechta kandidat kurinishi');
            $table->integer('code')->nullable()->comment('kod boshqa limmitlardan ajratish uchun');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('limits');
    }
};
