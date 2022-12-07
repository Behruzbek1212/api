<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('job_id');
            $table->string('job_slug')->default('hello-world');
        });
    }

    public function down()
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('job_slug');
            $table->bigInteger('job_id')->unsigned();
        });
    }
};
