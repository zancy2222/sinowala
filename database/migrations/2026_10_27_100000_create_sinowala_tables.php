<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('code', 6)->unique();
            $table->string('invite_token', 64)->unique();
            $table->string('status', 16)->default('pending')->index(); // pending | active | suspended
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('role', 16)->default('member'); // admin | member
            $table->timestamp('joined_at')->useCurrent();
            $table->index(['company_id', 'role']);
        });

        // Absences belong to the USER, never to a company.
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('part', 4)->default('full'); // full | am | pm
            $table->timestamps();
            $table->unique(['user_id', 'date']);
            $table->index('date');
        });

        // Private note, readable by the owner only. Encrypted at rest.
        Schema::create('absence_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absence_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email');
            $table->text('message');
            $table->string('ip', 45)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('privacy_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 120);
            $table->string('email');
            $table->string('type', 16); // access | correction | deletion | export | objection
            $table->text('details')->nullable();
            $table->string('status', 16)->default('open')->index(); // open | done
            $table->string('ip', 45)->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('consent_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->uuid('consent_id')->index();
            $table->string('version', 16);
            $table->json('choices');
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 64);
            $table->string('target_type', 64)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('target_label')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('consent_logs');
        Schema::dropIfExists('privacy_requests');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('absence_notes');
        Schema::dropIfExists('absences');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('companies');
    }
};
