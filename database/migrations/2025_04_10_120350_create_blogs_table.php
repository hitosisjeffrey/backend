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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('title');  // The blog title
            $table->text('content');  // The blog content
            $table->enum('status', ['DRAFT', 'PUBLISHED'])->default('DRAFT');  // Enum for blog status
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');  // Foreign key referencing users table
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
