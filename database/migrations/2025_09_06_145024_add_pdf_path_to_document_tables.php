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
        if (!Schema::hasColumn('borang_penanganans', 'pdf_path')) {
            Schema::table('borang_penanganans', function (Blueprint $table) {
                $table->string('pdf_path')->nullable();
            });
        }
        if (!Schema::hasColumn('berita_acara_pemeriksaans', 'pdf_path')) {
            Schema::table('berita_acara_pemeriksaans', function (Blueprint $table) {
                $table->string('pdf_path')->nullable();
            });
        }
        if (!Schema::hasColumn('borang_pemeriksaans', 'pdf_path')) {
            Schema::table('borang_pemeriksaans', function (Blueprint $table) {
                $table->string('pdf_path')->nullable();
            });
        }
        if (!Schema::hasColumn('laporan_hasil_pemeriksaans', 'pdf_path')) {
            Schema::table('laporan_hasil_pemeriksaans', function (Blueprint $table) {
                $table->string('pdf_path')->nullable();
            });
        }
        if (!Schema::hasColumn('surat_rekomendasis', 'pdf_path')) {
            Schema::table('surat_rekomendasis', function (Blueprint $table) {
                $table->string('pdf_path')->nullable();
            });
        }

        Schema::table('surat_panggilans', function (Blueprint $table) {
            if (Schema::hasColumn('surat_panggilans', 'file_path') && !Schema::hasColumn('surat_panggilans', 'pdf_path')) {
                $table->renameColumn('file_path', 'pdf_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('borang_penanganans', 'pdf_path')) {
            Schema::table('borang_penanganans', function (Blueprint $table) {
                $table->dropColumn('pdf_path');
            });
        }
        if (Schema::hasColumn('berita_acara_pemeriksaans', 'pdf_path')) {
            Schema::table('berita_acara_pemeriksaans', function (Blueprint $table) {
                $table->dropColumn('pdf_path');
            });
        }
        if (Schema::hasColumn('borang_pemeriksaans', 'pdf_path')) {
            Schema::table('borang_pemeriksaans', function (Blueprint $table) {
                $table->dropColumn('pdf_path');
            });
        }
        if (Schema::hasColumn('laporan_hasil_pemeriksaans', 'pdf_path')) {
            Schema::table('laporan_hasil_pemeriksaans', function (Blueprint $table) {
                $table->dropColumn('pdf_path');
            });
        }
        if (Schema::hasColumn('surat_rekomendasis', 'pdf_path')) {
            Schema::table('surat_rekomendasis', function (Blueprint $table) {
                $table->dropColumn('pdf_path');
            });
        }

        Schema::table('surat_panggilans', function (Blueprint $table) {
            if (Schema::hasColumn('surat_panggilans', 'pdf_path') && !Schema::hasColumn('surat_panggilans', 'file_path')) {
                $table->renameColumn('pdf_path', 'file_path');
            }
        });
    }
};
