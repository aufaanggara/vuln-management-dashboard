<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->id();

            // Field inti
            $table->string('title');
            $table->text('description');
            $table->string('vulnerability_type');
            $table->string('cwe_id')->nullable();
            $table->enum('severity', ['Critical', 'High', 'Medium', 'Low', 'Info']);
            $table->decimal('cvss_score', 3, 1)->nullable();
            $table->string('cvss_vector')->nullable();
            $table->string('affected_asset');
            $table->text('impact');
            $table->text('recommendation');
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Accepted Risk', 'False Positive'])
                ->default('Open');

            // Relasi & tanggal
            $table->foreignId('discovered_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('discovered_at');
            $table->date('resolved_at')->nullable();

            // Tambahan opsional
            $table->text('references')->nullable();
            $table->string('evidence_screenshot')->nullable();
            $table->boolean('client_visible')->default(false);

            // Field fleksibel — isi tergantung template per vulnerability_type
            $table->json('technical_details')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};