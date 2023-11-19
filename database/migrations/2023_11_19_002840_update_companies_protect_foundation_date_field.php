<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("set global log_bin_trust_function_creators = 1;");
        DB::unprepared('
            CREATE TRIGGER before_update_trigger
            BEFORE UPDATE ON companies
            FOR EACH ROW
            BEGIN
                IF NEW.company_foundation_date != OLD.company_foundation_date THEN
                    SIGNAL SQLSTATE \'45000\'
                    SET MESSAGE_TEXT = \'A regisztráció időpontját nem lehet módosítani!\';
                END IF;
            END;
        ');
        DB::unprepared("set global log_bin_trust_function_creators = 0;");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
        DROP TRIGGER IF EXISTS before_update_trigger;
    ');
    }
};
