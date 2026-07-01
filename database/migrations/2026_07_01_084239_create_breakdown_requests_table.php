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
        Schema::create('breakdown_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique(); // REQ#1258
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mechanic_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_category_id')->constrained()->onDelete('cascade');
            $table->text('problem_description');
            $table->decimal('user_latitude', 10, 7);
            $table->decimal('user_longitude', 10, 7);
            $table->string('user_address')->nullable();
            $table->enum('status', [
                'pending',
                'accepted',
                'on_the_way',
                'arrived',
                'repairing',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_requests');
    }
};
