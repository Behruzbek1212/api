<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->string('title_en')->after('id');
            $table->string('title_ru')->after('id');
            $table->string('title_uz')->after('id');

            $table->dropColumn('content');
            $table->text('content_en')->after('background');
            $table->text('content_ru')->after('background');
            $table->text('content_uz')->after('background');
        });
    }

    public function down()
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn(['title_uz', 'title_ru', 'title_en']);
            $table->dropColumn(['content_uz', 'content_ru', 'content_en']);

            $table->string('title')->after('id');
            $table->text('content')->after('background');
        });
    }
};
