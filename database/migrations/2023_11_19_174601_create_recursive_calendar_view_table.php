<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement('
        CREATE OR REPLACE VIEW view_recursive_calendar AS
        WITH RECURSIVE seq AS (
            SELECT "2001-01-01" AS date UNION ALL SELECT date + interval 1 day FROM seq WHERE date < CURDATE()
          ) SELECT date FROM seq;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_recursive_calendar');
    }
};
