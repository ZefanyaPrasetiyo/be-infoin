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
                $table->id();
                $table->foreignId("id_user")->constrained("users")->onDelete("cascade");
                $table->foreignId("id_report")->constrained("reports")->onDelete("cascade");
                $table->text('message')->nullable();
                $table->foreignId("id_parent")->nullable()->constrained("comments")->onDelete("cascade");

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
