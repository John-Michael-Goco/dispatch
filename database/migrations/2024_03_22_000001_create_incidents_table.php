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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable(); 
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('status')->default('open'); // open, in_progress, resolved, closed
            $table->foreignId('reported_by')->constrained('users');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('branch_id')->constrained('branches');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
}; 