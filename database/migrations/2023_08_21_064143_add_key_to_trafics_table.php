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
        Schema::table('trafics', function (Blueprint $table) {
            $table->integer('trafic_price_id')->unsigned()->after('id')->nullable();
            $table->string('key')->after('type')->nullable()->comment('for_site,for_telegram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trafics', function (Blueprint $table) {
            $table->dropColumn('trafic_price_id');
            $table->dropColumn('key');
        });
    }
};
