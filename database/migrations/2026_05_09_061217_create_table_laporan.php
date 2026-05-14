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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->constrained("users")->onDelete("cascade");
            $table->foreignId("id_kategori")->constrained("kategori")->onDelete("cascade");
            $table->text('deskripsi');
            $table->string('bukti_laporan')->nullable();
            $table->enum('status', ["menunggu", "diproses", "disetujui"])->default("menunggu");
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kepercayaan_ai')->nullable();
            $table->string('label_ai')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
