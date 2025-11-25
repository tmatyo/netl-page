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
        Schema::create('crawlings', function (Blueprint $table) {
            $table->id();
            $table->string('table_name', 255);
            $table->datetime('time_generated');
            $table->float('crawling_duration');
            $table->float('download_duration');
            $table->integer('avg_speed_in_bytes_per_sec');
            $table->integer('file_size');
            $table->integer('domain_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawlings');
    }
};
