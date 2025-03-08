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
        Schema::create('korlurs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->bigInteger('phone');
            $table->bigInteger('nik')->unique();
            $table->string('tgl_lahir');
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->integer('kelurahan_id');
            $table->integer('korcam_id');
            $table->integer('kabkota_id');
            $table->integer('partai_id');
            $table->integer('tps_id');
            $table->integer('user_id');
            $table->string('keterangan');
            $table->enum('status',['1', '2']);
            $table->enum('deleted', ['0','1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('korlurs');
    }
};
