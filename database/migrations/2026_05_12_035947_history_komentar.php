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
        Schema::create('history_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->constrained("users")->onDelete("cascade");
            $table->foreignId("id_komentar")->constrained("comments")->onDelete("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_comments');
    }
};
