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
        Schema::create('agent_tps', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('phone');
            $table->string('kabkota_id');
            $table->string('tgl_lahir');
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->integer('korlur_id');
            $table->integer('kelurahan_id');
            $table->integer('partai_id');
            $table->enum('is_koordinator', ['0','1'])->default('0');
            $table->enum('status',['1', '2']);
            $table->integer('user_id');
            $table->string('keterangan');
            
            $table->enum('deleted', ['0','1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_tps');
    }
};
