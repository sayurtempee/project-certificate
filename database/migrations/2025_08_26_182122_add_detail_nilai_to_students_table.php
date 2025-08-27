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
        Schema::table('students', function (Blueprint $table) {
            $table->integer('kelancaran')->default(0);
            $table->integer('fasohah')->default(0);
            $table->integer('tajwid')->default(0);
            $table->integer('total_kesalahan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('kelancaran');
            $table->dropColumn('fasohah');
            $table->dropColumn('tajwid');
            $table->dropColumn('total_kesalahan');
        });
    }
};
