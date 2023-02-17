<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('candidate', function (Blueprint $table) {
            $table->date('__conversation_date')->after('__conversation');
        });
    }

    public function down()
    {
        Schema::table('candidate', function (Blueprint $table) {
            $table->dropColumn(['__conversation_date']);
        });
    }
};
