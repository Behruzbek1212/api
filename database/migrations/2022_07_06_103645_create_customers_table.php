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
        $default_avatar = 'https://static.jobo.uz/img/default.webp';

        Schema::create('customers', function (Blueprint $table) use ($default_avatar) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('avatar')->default($default_avatar);
            $table->string('name');
            $table->bigInteger('balance')->default(0);
            $table->timestamp('owned_date');
            $table->string('address');
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('customers');
    }
};
