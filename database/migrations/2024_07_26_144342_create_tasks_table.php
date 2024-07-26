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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tag');
            $table->integer('estimated_hours')->nullable();
            $table->integer('user_id');
            $table->integer('assigner_id')->nullable();
            $table->integer('assignee_id')->nullable();
            $table->enum('status', ['todo', 'in-progress', 'done'])->default('todo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
