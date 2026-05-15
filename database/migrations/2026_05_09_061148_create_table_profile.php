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
        Schema::create('profile', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlId("id_user")->constrained("users")->onDelete("cascade");
            $table->string('nama_panjang');
            $table->string('foto_profile')->nullable();
            $table->string('bio')->nullable();
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->enum('jenis_kelamin',["laki-laki", "perempuan"]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile');
    }
};
