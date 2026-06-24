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
        Schema::create('detail_reports', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlId("id_report")->constrained("reports")->onDelete("cascade");
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('alamat')->nullable();
            $table->foreignUlid('id_location')->constrained("locations")->onDelete("cascade");
            $table->string('kepercayaan_ai')->nullable();
            $table->string('label_ai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_reports');
    }
};