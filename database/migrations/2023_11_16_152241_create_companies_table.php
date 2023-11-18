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
        Schema::create('companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('company_name', 200);
            $table->string('company_registration_number', 20);
            $table->date('company_foundation_date');
            $table->string('country', 50);
            $table->string('zip_code', 20);
            $table->string('city', 50);
            $table->string('street_address', 100);
            $table->string('latitude', 100);
            $table->string('longitude', 100);
            $table->string('company_owner', 100);
            $table->string('employees', 100);
            $table->string('activity', 100);
            $table->boolean('active');
            $table->string('email', 100);
            $table->string('password', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
