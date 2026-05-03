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
        Schema::create('mesh_peers', function (Blueprint $table) {
            $table->id();
            $table->string('node_id')->unique();        // e.g., edge-node-02
            $table->string('node_secret')->nullable();  // Identity Token
            $table->string('status')->default('active'); // active/offline status
            $table->integer('version')->default(1);     // Lamport Clock version
            $table->string('security_mode')->default('trusted_subnet'); // Security Transparency
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesh_peers');
    }
};
