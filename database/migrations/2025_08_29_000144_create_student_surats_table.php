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
        Schema::create('student_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('surat_ke');
            $table->string('nama_surat');
            $table->integer('ayat');
            $table->integer('kelancaran')->default(0);
            $table->integer('fasohah')->default(0);
            $table->integer('tajwid')->default(0);
            $table->integer('total_kesalahan')->default(0);
            $table->integer('nilai')->default(0);
            $table->string('predikat')->default('D');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_surats');
    }
};
