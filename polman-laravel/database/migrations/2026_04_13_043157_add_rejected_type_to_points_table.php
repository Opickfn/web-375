<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // For PostgreSQL, drop the existing check constraint and add a new one
            DB::statement("ALTER TABLE points DROP CONSTRAINT IF EXISTS points_type_check");
            DB::statement("ALTER TABLE points ADD CONSTRAINT points_type_check CHECK (type IN ('submit', 'approved', 'bonus', 'rejected'))");
        } elseif ($driver === 'mysql') {
            // For MySQL, we can modify the enum column
            Schema::table('points', function (Blueprint $table) {
                $table->string('type')->change(); // Change to string first to avoid enum issues
            });
            Schema::table('points', function (Blueprint $table) {
                $table->enum('type', ['submit', 'approved', 'bonus', 'rejected'])->change();
            });
        } else {
            // SQLite - no changes needed as it doesn't have enum type
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE points DROP CONSTRAINT IF EXISTS points_type_check");
            DB::statement("ALTER TABLE points ADD CONSTRAINT points_type_check CHECK (type IN ('submit', 'approved', 'bonus'))");
        } elseif ($driver === 'mysql') {
            Schema::table('points', function (Blueprint $table) {
                $table->string('type')->change();
            });
            Schema::table('points', function (Blueprint $table) {
                $table->enum('type', ['submit', 'approved', 'bonus'])->change();
            });
        }
    }
};
