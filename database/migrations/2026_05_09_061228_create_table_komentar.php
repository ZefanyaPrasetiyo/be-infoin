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
            Schema::create('comments', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlId("id_user")->constrained("users")->onDelete("cascade");
                $table->foreignUlId("id_report")->constrained("reports")->onDelete("cascade");
                $table->text('message')->nullable();
                $table->foreignUlId("id_parent")->nullable()->constrained("comments")->onDelete("cascade");

                $table->timestamps();
                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('comments');
        }
    };
