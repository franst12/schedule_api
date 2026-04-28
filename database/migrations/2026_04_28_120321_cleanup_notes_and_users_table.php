<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Menghapus note_link di tabel notes
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'note_link')) {
                $table->dropColumn('note_link');
            }
        });

        // 2. Mengubah user_id menjadi id di tabel users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'user_id')) {
                $table->renameColumn('user_id', 'id');
            }
        });

        // 3. PERBAIKAN RELASI (Sangat Penting untuk Flutter temen kamu)
        Schema::table('notes', function (Blueprint $table) {
            // Hapus hubungan lama yang masih nyambung ke tabel schedules
            // Kita pakai nama asli constraint-nya sesuai error temen kamu
            $table->dropForeign('notes_schedule_id_foreign');

            // Buat hubungan baru agar course_id benar-benar nyambung ke tabel courses
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->string('note_link')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id')) {
                $table->renameColumn('id', 'user_id');
            }
        });
    }
};