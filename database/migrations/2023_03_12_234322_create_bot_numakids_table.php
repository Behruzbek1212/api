<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bot_numakids', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('telegram_id')->nullable();
            $table->string('identification');
            $table->json('info');
            $table->timestamps();
        });

        Schema::create('bot_numakids_craters', function (Blueprint $table) {
            $table->id();
            $table->string('identification');
            $table->string('url');
            $table->string('image');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_numakids');
        Schema::dropIfExists('bot_numakids_craters');
    }
};
