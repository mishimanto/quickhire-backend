<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('open_jobs')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('resume_link');
            $table->text('cover_note')->nullable();
            $table->string('status')->default('pending'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};