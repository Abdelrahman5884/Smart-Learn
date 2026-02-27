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
        Schema::table('course_lectures', function (Blueprint $table) {
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->integer('video_duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_lectures', function (Blueprint $table) {
            //
        });
    }
};
