<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->enum('kategori', ['5R', '7S', 'K3']);
            $table->string('lokasi', 255);
            $table->text('deskripsi');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi']);
            $table->enum('status', ['pending', 'approved', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->string('bukti', 255)->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
