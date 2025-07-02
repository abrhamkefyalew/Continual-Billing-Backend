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
        Schema::create('device_traffic', function (Blueprint $table) {
            $table->id();

            // Device data columns
            $table->string('device_type')->nullable();        // e.g. "web"
            $table->text('user_agent')->nullable();   // long user agents
            $table->ipAddress('ip')->nullable();              // e.g. "196.189.18.166"
            $table->ipAddress('ip_got_using_custom_function')->nullable(); // optional for tracking internal logic
            $table->text('url')->nullable();                  // e.g. "http://34.65.224.178:8400/log-viewer"
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_traffic');
    }
};
