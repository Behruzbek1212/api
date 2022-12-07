<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payment_system');
            $table->double('amount', 15, 5);
            $table->integer('currency_code');
            $table->string('system_transaction_id');
            $table->integer('state');
            $table->string('comment')->nullable();
            $table->json('detail')->nullable();
            $table->string('transactionable_type')->nullable();
            $table->integer('transactionable_id')->nullable();
            $table->string('updated_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
