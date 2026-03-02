<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecture_user', function (Blueprint $table) {

            $table->id();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['lecture_id','user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecture_user');
    }
};