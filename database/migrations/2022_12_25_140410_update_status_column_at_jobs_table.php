<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE `jobs` MODIFY COLUMN `status` ENUM('approved', 'rejected', 'moderating', 'closed') NOT NULL DEFAULT 'moderating'");
        DB::statement("ALTER TABLE `chats` MODIFY COLUMN `status` ENUM('approve', 'reject', 'review', 'closed') NOT NULL DEFAULT 'review'");

        // Schema::table('jobs', function (Blueprint $table) {
        //     $table->dropColumn('status');
        //     $table->enum('status', ['approved', 'rejected', 'moderating', 'closed'])->default('moderating')->after('slug');
        // });

        // Schema::table('chats', function (Blueprint $table) {
        //     $table->dropColumn('status');
        //     $table->enum('status', ['approved', 'reject', 'review', 'closed'])->default('review')->after('candidate_id');
        // });
    }

    public function down()
    {
        DB::statement("ALTER TABLE `jobs` MODIFY COLUMN `status` ENUM('approved', 'rejected', 'moderating') NOT NULL DEFAULT 'moderating'");
        DB::statement("ALTER TABLE `chats` MODIFY COLUMN `status` ENUM('approve', 'reject', 'review') NOT NULL DEFAULT 'review'");

        // Schema::table('jobs', function (Blueprint $table) {
        //     $table->dropColumn('status');
        //     $table->enum('status', ['approved', 'rejected', 'moderating'])->default('moderating')->after('slug');
        // });

        // Schema::table('chats', function (Blueprint $table) {
        //     $table->dropColumn('status');
        //     $table->enum('status', ['approved', 'reject', 'review'])->default('review')->after('candidate_id');
        // });
    }
};
