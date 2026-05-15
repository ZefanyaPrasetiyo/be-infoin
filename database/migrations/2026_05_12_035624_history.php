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
            $table->ulid('id')->primary();
            $table->foreignUlId("id_user")->constrained("users")->onDelete("cascade");
            $table->foreignUlId("id_laporan")->constrained("reports")->onDelete("cascade");
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
