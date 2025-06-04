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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');
            $table->double('duration')->default(0);
            $table->double('rating')->default(5);
            $table->text('about');
            $table->integer('average_salary')->default(1000);

            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('difficulty_level_id');

            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('difficulty_level_id')->references('id')->on('difficulty_levels');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
