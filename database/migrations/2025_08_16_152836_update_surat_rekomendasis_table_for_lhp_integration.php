    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('surat_rekomendasis', function (Blueprint $table) {
                $table->foreignId('laporan_hasil_pemeriksaan_id')->after('user_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('status_terbukti')->after('laporan_hasil_pemeriksaan_id');
                $table->json('rekomendasi_data')->after('tembusan')->nullable();
            });
        }

        public function down(): void
        {
            Schema::table('surat_rekomendasis', function (Blueprint $table) {
                $table->dropForeign(['laporan_hasil_pemeriksaan_id']);
                $table->dropColumn(['laporan_hasil_pemeriksaan_id', 'status_terbukti', 'rekomendasi_data']);
            });
        }
    };
