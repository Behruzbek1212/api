<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('password_verification', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->index()->nullable();
            $table->string('email')->index()->nullable();
            $table->string('token');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_verification');
    }
};
