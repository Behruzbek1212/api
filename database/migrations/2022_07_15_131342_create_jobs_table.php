<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('title');
            $table->string('type');
            $table->json('salary');
            $table->json('requirements');
            $table->json('tasks')->nullable();
            $table->json('advantages')->nullable();
            $table->integer('location_id');
            $table->string('slug')->unique();
            $table->enum('status', ['approved', 'rejected', 'moderating'])->default('moderating');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
