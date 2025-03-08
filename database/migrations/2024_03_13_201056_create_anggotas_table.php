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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('phone');
            $table->string('jenis_kelamin');
            $table->string('usia');
            $table->string('kabkota_id');
            $table->string('tgl_lahir');
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->integer('tps_id');
            $table->integer('agent_id');
            $table->enum('status',['1', '2']);
            $table->string('keterangan');
            $table->enum('verified',['0','1', '2'])->default('0');
            $table->enum('deleted', ['0','1'])->default('0');
            $table->integer('user_id');
            $table->integer('verified_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
