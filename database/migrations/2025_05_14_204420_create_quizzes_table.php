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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('question_count');
            $table->integer('time_per_question')->default(1); // 1 minuto por defecto
            $table->integer('attempts')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->datetime('start_date'); // Cambié a datetime
            $table->datetime('end_date'); // Cambié a datetime
            $table->enum('access', ['public', 'private', 'group'])->default('public');
            $table->boolean('show_results')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
