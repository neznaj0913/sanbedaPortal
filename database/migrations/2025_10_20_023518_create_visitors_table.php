<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            // ✅ Gate Pass Number
            $table->string('gatepass_no')->unique();

            // ✅ Basic Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('department');
            $table->string('company_affiliation')->nullable();

            // ✅ Contact Information
            $table->string('contact_person'); // Who they are visiting
            $table->string('contact_info')->nullable(); // Contact info of that person

            // ✅ Visit Purpose
            $table->string('purpose');
            $table->text('additional_notes')->nullable();

            // ✅ Tracking
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->string('status')->default('Inside'); // "Inside" or "Outside"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
