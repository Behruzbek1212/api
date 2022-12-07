<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['requirements', 'advantages', 'tasks']);
            $table->text('about')->nullable()->after('salary');
            $table->enum('work_type', [
                'fulltime', 'remote', 'partial', 'hybrid'
            ])->default('hybrid')->after('salary');
            $table->string('experience')->default('0')->after('salary');
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->json('requirements');
            $table->json('tasks')->nullable();
            $table->json('advantages')->nullable();
            $table->dropColumn(['about', 'work_type', 'experience']);
        });
    }
};
