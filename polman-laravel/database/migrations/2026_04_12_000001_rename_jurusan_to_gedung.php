<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // Step 1: Rename jurusans table to gedungs
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE jurusans RENAME TO gedungs');
        } elseif ($driver === 'mysql') {
            DB::statement('RENAME TABLE jurusans TO gedungs');
        } else {
            // SQLite
            DB::statement('ALTER TABLE jurusans RENAME TO gedungs');
        }

        // Step 2: Rename program_studis table to ruangans - this will require fixing the foreign key
        // First, we need to drop and recreate the program_studis table to change the foreign key
        if ($driver === 'mysql') {
            // For MySQL, use a simpler approach
            Schema::table('program_studis', function (Blueprint $table) {
                // Get all constraints and drop them
                DB::statement('ALTER TABLE program_studis DROP FOREIGN KEY program_studis_jurusan_id_foreign');
            });

            // Rename column
            DB::statement('ALTER TABLE program_studis CHANGE COLUMN jurusan_id gedung_id BIGINT UNSIGNED NOT NULL');

            // Recreate table with new name
            DB::statement('RENAME TABLE program_studis TO ruangans');

            // Add foreign key back
            Schema::table('ruangans', function (Blueprint $table) {
                $table->foreign('gedung_id')->references('id')->on('gedungs')->onDelete('cascade');
            });
        } elseif ($driver === 'pgsql') {
            // For PostgreSQL
            // Rename column in program_studis
            DB::statement('ALTER TABLE program_studis RENAME COLUMN jurusan_id TO gedung_id');

            // Now rename the table
            DB::statement('ALTER TABLE program_studis RENAME TO ruangans');
        } else {
            // SQLite
            DB::statement('ALTER TABLE program_studis RENAME TO ruangans');
        }

        // Step 3: Update users table columns
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users RENAME COLUMN jurusan TO gedung');
            DB::statement('ALTER TABLE users RENAME COLUMN program_studi TO ruangan');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE users CHANGE jurusan gedung VARCHAR(100) NULL');
            DB::statement('ALTER TABLE users CHANGE program_studi ruangan VARCHAR(100) NULL');
        } else {
            // SQLite
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('jurusan', 'gedung');
                $table->renameColumn('program_studi', 'ruangan');
            });
        }

        // Step 4: Add gedung_id column to users table for manager building assignment
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gedung_id')) {
                $table->unsignedBigInteger('gedung_id')->nullable()->after('ruangan');
                $table->foreign('gedung_id')->references('id')->on('gedungs')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        // Remove gedung_id column and foreign key
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'gedung_id')) {
                $table->dropForeign(['gedung_id']);
                $table->dropColumn('gedung_id');
            }
        });

        // Rename users table columns back
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users RENAME COLUMN gedung TO jurusan');
            DB::statement('ALTER TABLE users RENAME COLUMN ruangan TO program_studi');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE users CHANGE gedung jurusan VARCHAR(100) NULL');
            DB::statement('ALTER TABLE users CHANGE ruangan program_studi VARCHAR(100) NULL');
        } else {
            // SQLite
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('gedung', 'jurusan');
                $table->renameColumn('ruangan', 'program_studi');
            });
        }

        // Rename ruangans back to program_studis
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE ruangans RENAME COLUMN gedung_id TO jurusan_id');
            DB::statement('ALTER TABLE ruangans RENAME TO program_studis');
        } elseif ($driver === 'mysql') {
            DB::statement('RENAME TABLE ruangans TO program_studis');
            Schema::table('program_studis', function (Blueprint $table) {
                DB::statement('ALTER TABLE program_studis DROP FOREIGN KEY ruangans_gedung_id_foreign');
            });
            DB::statement('ALTER TABLE program_studis CHANGE COLUMN gedung_id jurusan_id BIGINT UNSIGNED NOT NULL');
            Schema::table('program_studis', function (Blueprint $table) {
                $table->foreign('jurusan_id')->references('id')->on('jurusans')->onDelete('cascade');
            });
        } else {
            // SQLite
            DB::statement('ALTER TABLE ruangans RENAME TO program_studis');
        }

        // Rename gedungs back to jurusans
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE gedungs RENAME TO jurusans');
        } elseif ($driver === 'mysql') {
            DB::statement('RENAME TABLE gedungs TO jurusans');
        } else {
            // SQLite
            DB::statement('ALTER TABLE gedungs RENAME TO jurusans');
        }
    }
};
