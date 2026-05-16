<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->unique();
            $table->date('date');
            $table->string('attachment_count')->nullable();
            $table->string('subject');
            $table->string('recipient_name');
            $table->string('recipient_place')->nullable();
            $table->string('sender_name');
            $table->string('sender_position');
            $table->text('sender_address')->nullable();
            $table->text('body');
            $table->string('place')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
