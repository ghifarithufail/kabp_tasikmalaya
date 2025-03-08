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
        Schema::create('korcams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nik')->unique();
            $table->string('nama');
            $table->string('kabkota_id');
            $table->string('tgl_lahir');
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->string('kecamatan_id');
            $table->string('phone');
            $table->enum('status',['1', '2']);
            $table->string('keterangan')->nullable();
            $table->integer('partai_id');
            $table->integer('user_id');
            $table->string('kordapil')->nullable();
            $table->enum('deleted', ['0','1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('korcams');
    }
};
