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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // receiver (practice or specialist)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // optional sender (system / practice / specialist)
            $table->foreignId('sender_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // event + tab
            $table->string('type', 50);              // application_received, confirmation, etc.
            $table->string('category', 50)->default('all'); // all, announcement, document, ...

            // content
            $table->string('title', 255);            // e.g. "John Doe: Dental Assistant 1"
            $table->longText('body')->nullable();

            // read state
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // optional template key
            $table->string('template')->nullable();

            // link to job / application / other models
            $table->morphs('notifiable');           // notifiable_id, notifiable_type

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
