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
        Schema::create('enterprise_users', function (Blueprint $table) {
            $table->id()->from(10000);

            $table->foreignId('enterprise_id')->constrained('enterprises');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_admin')->default(0); // 1 or 0 // to check if the enterprise_user have admin privilege // default 0
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // profile picture will be contained in media table , if needed
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_users');
    }
};
