<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(-1);
            $table->integer('candidate_id')->default(-1);
            $table->integer('customer_id')->default(-1);
            $table->string('job_slug')->default('0');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->integer('visits')->default(0)->after('data');
            $table->integer('downloads')->default(0)->after('data');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stats');

        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn('visits');
            $table->dropColumn('downloads');
        });
    }
};
