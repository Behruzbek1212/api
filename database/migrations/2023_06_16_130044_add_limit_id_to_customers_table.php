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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('limit_id')->nullable()->constrained('limits');
            $table->dateTime('limit_start_day')->nullable()->comment('boshlanish sanasi');
            $table->dateTime('limit_end_day')->nullable()->comment('tugash sanasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('limit_id');
            $table->dropColumn('limit_start_day');
            $table->dropColumn('limit_end_day');
        });
    }
};

?>

