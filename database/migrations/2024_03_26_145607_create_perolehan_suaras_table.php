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
        Schema::create('perolehan_suaras', function (Blueprint $table) {
            $table->id();
            $table->string("total_suara");
            $table->string("caleg_id");
            $table->string("tps_id");
            $table->string("bukti_pleno")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perolehan_suaras');
    }
};
