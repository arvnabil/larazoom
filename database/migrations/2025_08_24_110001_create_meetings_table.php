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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
            $table->foreignId('zoom_host_id')->constrained('zoom_hosts')->onDelete('cascade');
            $table->string('topic'); // Judul meeting, bisa sama dengan judul topik
            $table->string('password'); // Judul meeting, bisa sama dengan judul topik
            $table->dateTime('start_time');
            $table->integer('duration'); // In minutes
            $table->bigInteger('zoom_meeting_id')->unique();
            $table->text('zoom_start_url');
            $table->text('zoom_join_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
