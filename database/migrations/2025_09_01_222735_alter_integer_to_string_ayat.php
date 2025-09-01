<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_surats', function (Blueprint $table) {
            $table->string('ayat')->change(); // ubah ke VARCHAR
        });
    }

    public function down(): void
    {
        Schema::table('student_surats', function (Blueprint $table) {
            $table->integer('ayat')->change(); // rollback ke INTEGER
        });
    }
};
