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
        Schema::table('trafic_prices', function (Blueprint $table) {
            $table->string('count')->after('id')->comment('elonlar soni')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trafic_prices', function (Blueprint $table) {
            $table->dropColumn('count');
        });
    }
};
