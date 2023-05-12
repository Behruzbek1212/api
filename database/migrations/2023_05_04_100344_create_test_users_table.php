<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
//     'name',
//        'surname',
//        'sex',
//        'position',
//        'phone',
//        'test',

    public function up()
    {
        Schema::create('test_users', function (Blueprint $table) {
            $table->id()->startingValue(1);
            $table->string('name',20)->nullable();
            $table->string('surname', 20)-> nullable();
            $table->string('position', 30)->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->string('phone')->unique()->nullable();
            $table->string('company_id');
            $table->json('test')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_users');
    }
};
