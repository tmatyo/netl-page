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
        Schema::create('domain_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('data_length_in_bytes');
            $table->float('data_extraction_duration_in_seconds');
            $table->string('longest_domain_name', 255);
            $table->integer('longest_domain_name_length');
            $table->float('avg_domain_name_length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_statistics');
    }
};
