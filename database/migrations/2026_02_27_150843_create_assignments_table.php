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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
            $table->string('title');
            $table->longText('description');
            $table->integer('max_grade')->default(100);
            $table->date('due_date');
            $table->string('attachment')->nullable();
            $table->enum('status', ['draft','published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
