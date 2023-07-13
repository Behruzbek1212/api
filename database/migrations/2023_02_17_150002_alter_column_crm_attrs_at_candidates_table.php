<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->boolean('__conversation')->default(false)->after('active');
            $table->text('__comment')->nullable()->after('active');
        });
    }

    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['__conversation', '__comment']);
        });
    }
};
