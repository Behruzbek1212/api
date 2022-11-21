<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bot_adson_craters', function (Blueprint $table) {
            $table->string('image')->after('url')
                ->default('https://raw.githubusercontent.com/adson-agency/.github/main/images/cover-dark.png');
        });
    }

    public function down()
    {
        Schema::table('bot_adson_craters', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
