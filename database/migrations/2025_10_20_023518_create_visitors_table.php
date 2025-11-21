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
            $table->string('gatepass_no')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('department');
            $table->string('company_affiliation')->nullable();
            $table->string('contact_person'); 
            $table->string('contact_info')->nullable(); 
            $table->string('purpose');
            $table->text('additional_notes')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->string('status')->default('Inside'); 
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
