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
            $table->string('nip_kepsek')->nullable()->after('nm_kepsek');
        });
    }

    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('nip_kepsek');
        });
    }
};
