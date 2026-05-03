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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('node_id');       // The node that performed the audit
            $table->float('latency_ms');     // The latency metric recorded (e.g., 212.88ms)
            $table->string('status');         // e.g., 'verified_clinical'
            $table->integer('clock_version'); // Lamport clock version for conflict resolution
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
