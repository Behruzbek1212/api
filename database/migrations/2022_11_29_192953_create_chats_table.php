<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('job_slug');
            $table->bigInteger('resume_id')->nullable();
            $table->bigInteger('customer_id');
            $table->bigInteger('candidate_id');
            $table->enum('status', ['approve', 'reject', 'review']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
