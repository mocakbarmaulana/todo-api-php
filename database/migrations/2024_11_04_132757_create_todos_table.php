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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->boolean("completed")->default(false);
            $table->dateTime("completed_at")->nullable();
            $table->dateTime("due_date")->nullable();
            $table->foreignId("user_id")->constrained();
            $table->index(["user_id", "completed"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
