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
        Schema::create('trigger_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trigger_id');
            $table->foreignId('notification_template_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('extra_recipients')->nullable();
            $table->boolean('enabled')->default(true);
            $table->json('trigger_action_data')->nullable();
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->foreignId('destroyer_id')->nullable();
            $table->foreignId('restorer_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('restored_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_actions');
    }
};
