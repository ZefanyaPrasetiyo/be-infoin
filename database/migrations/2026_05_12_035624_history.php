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
        Schema::create('report_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->constrained("users")->onDelete("cascade");
            $table->foreignId("id_laporan")->constrained("laporan")->onDelete("cascade");
            $table->enum('status', ["menunggu", "diproses", "disetujui"])->default("menunggu");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_history');
    }
};
