<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bot_adson', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('telegram_id')->nullable();
            $table->string('identification');
            $table->json('info');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_adson');
    }
};
