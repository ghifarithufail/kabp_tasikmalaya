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
        Schema::create('calons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('partai')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('kategori', ['walkot', 'bupati', 'gubernur'])->nullable();
            $table->enum('daerah_pemilihan', ['garut', 'kota tasikmalaya', 'kabupaten tasikmalaya', 'jawa barat']);
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon');
    }
};
