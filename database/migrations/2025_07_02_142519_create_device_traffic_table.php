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

            // Device Owner or user (i.e. if he is logged in) Columns
            $table->string('user_id')->nullable(); // NOT constrained , i.e. other servers may hit this server
            $table->text('user_id_description')->nullable(); // we want to store which table the user belongs in = e.g. App\Models\User

            
            // Device data columns
            $table->string('device_type')->nullable();        // e.g. "web"
            $table->text('user_agent')->nullable();   // long user agents
            $table->ipAddress('ip')->nullable();              // e.g. "196.189.18.166"
            $table->ipAddress('ip_got_using_custom_function')->nullable(); // for tracking IPs Real Source IPs internal logic
            $table->ipAddress('ip_behind_proxy_or_broadcast')->nullable(); // OPTIONAL for tracking IPs behind Proxy servers
            $table->ipAddress('ip_advanced_deep_tracing')->nullable(); //
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
