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
            $table->ulid('id')->primary();
            $table->string('kode_report')->nullable();
            $table->foreignUlId("id_user")->constrained("users")->onDelete("cascade");
            $table->foreignUlId("id_kategori")->constrained("categories")->onDelete("cascade");
            $table->text('deskripsi');
            $table->text('judul_laporan');
            $table->enum('status', ["menunggu", "diproses", "disetujui", "ditolak"])->default("menunggu");
           $table->json('bukti_laporan');
            $table->text('catatan');
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