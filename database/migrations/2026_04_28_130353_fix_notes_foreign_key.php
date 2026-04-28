<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Kita paksa hapus foreign key yang salah alamat itu
            // Nama 'notes_schedule_id_foreign' didapat dari pesan error kamu
            $table->dropForeign('notes_schedule_id_foreign');

            // Kita buat hubungan baru yang bener ke tabel courses
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
        });
    }
};