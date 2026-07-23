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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('notify_request_updates')->default(true);
            $table->boolean('notify_mechanic_nearby')->default(true);
            $table->boolean('notify_chat')->default(true);
            $table->boolean('notify_promotions')->default(false);
            $table->boolean('notify_requests')->default(true);
            $table->boolean('notify_messages')->default(true);
            $table->boolean('notify_reviews')->default(true);
            $table->boolean('notify_payments')->default(true);
            $table->boolean('share_location')->default(true);
            $table->boolean('show_profile')->default(true);
            $table->boolean('show_earnings')->default(true);
            $table->boolean('two_factor')->default(false);
            $table->string('language')->default('English');
            $table->string('currency')->default('KSh');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
