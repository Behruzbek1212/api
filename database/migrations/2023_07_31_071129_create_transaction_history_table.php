<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('service_id')->unsigned()->nullable()->comment('sotib olgan xizmat id si');
            $table->double('service_sum', 15, 5)->default(0)->nullable()->comment('sotib olgan xizmat summasi');
            $table->string('service_name')->nullable()->comment('sotib olgan xizmat nomi');
            $table->dateTime('started_at')->nullable()->comment('xizmatni sotib olgan sana');
            $table->dateTime('expire_at')->nullable()->comment('xizmatni amal qilish muddati');
            $table->string('key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_history');
    }
};
