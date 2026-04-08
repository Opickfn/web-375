<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('role', ['reporter', 'manager', 'admin'])->default('reporter');
            $table->enum('user_type', ['mahasiswa', 'dosen', 'umum'])->default('umum');
            $table->string('phone', 20)->nullable();

            // Mahasiswa fields
            $table->string('nim', 20)->nullable()->unique();
            $table->string('kelas', 20)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('program_studi', 100)->nullable();
            $table->string('tahun_angkatan', 4)->nullable();

            // Dosen fields
            $table->string('nomor_dosen', 30)->nullable()->unique();
            $table->string('jabatan', 100)->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
