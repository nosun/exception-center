<?php
declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('exception_events', function (Blueprint $table): void {
            $table->id(); $table->uuid('event_id')->unique(); $table->dateTime('occurred_at', 6); $table->dateTime('reported_at', 6);
            $table->string('project_code',64); $table->string('repository_code',128); $table->string('instance_code',64); $table->string('environment',32); $table->string('service',128);
            $table->string('category',32); $table->string('exception_type',160); $table->string('severity',8); $table->char('fingerprint',64); $table->string('summary',1000);
            $table->string('exception_class')->nullable(); $table->string('operation')->nullable(); $table->string('entity_type',64)->nullable(); $table->string('entity_id',128)->nullable();
            $table->string('request_id',128)->nullable(); $table->string('trace_id',128)->nullable(); $table->string('source_version',128)->nullable(); $table->string('source_host')->nullable(); $table->string('source_url',2000)->nullable();
            $table->json('context_json')->nullable(); $table->text('stack_trace')->nullable(); $table->timestamps(6);
            $table->index(['project_code','instance_code','environment','occurred_at'], 'exception_events_scope_idx'); $table->index(['fingerprint','occurred_at'], 'exception_events_fp_idx'); $table->index(['exception_type','occurred_at'], 'exception_events_type_idx'); $table->index('reported_at', 'exception_events_reported_idx');
        });
        Schema::create('exception_groups', function (Blueprint $table): void {
            $table->id(); $table->char('fingerprint',64); $table->string('project_code',64); $table->string('repository_code',128); $table->string('instance_code',64); $table->string('environment',32); $table->string('category',32); $table->string('exception_type',160); $table->string('severity',8); $table->dateTime('first_seen_at',6); $table->dateTime('last_seen_at',6); $table->unsignedBigInteger('occurrence_count')->default(0); $table->uuid('sample_event_id'); $table->string('status',32)->default('new'); $table->string('assigned_agent',128)->nullable(); $table->dateTime('assigned_at',6)->nullable(); $table->dateTime('lease_until',6)->nullable(); $table->dateTime('resolved_at',6)->nullable(); $table->string('resolution_code',64)->nullable(); $table->string('resolution_summary',2000)->nullable(); $table->timestamps(6); $table->index(['fingerprint','instance_code','environment'], 'exception_groups_scope_idx'); $table->index(['status','last_seen_at'], 'exception_groups_status_idx');
        });
        Schema::create('exception_dispatches', function (Blueprint $table): void { $table->id(); $table->foreignId('group_id')->constrained('exception_groups'); $table->uuid('dispatch_id')->unique(); $table->string('agent_code',128); $table->string('project_code',64); $table->string('repository_code',128); $table->string('workspace',512)->nullable(); $table->json('prompt_payload_json'); $table->string('status',32)->default('pending'); $table->unsignedInteger('attempts')->default(0); $table->dateTime('leased_at',6)->nullable(); $table->dateTime('lease_until',6)->nullable(); $table->dateTime('started_at',6)->nullable(); $table->dateTime('finished_at',6)->nullable(); $table->string('result_code',64)->nullable(); $table->string('result_summary',2000)->nullable(); $table->json('result_payload_json')->nullable(); $table->string('last_error',2000)->nullable(); $table->timestamps(6); $table->index(['status','lease_until'], 'exception_dispatches_claim_idx'); });
        Schema::create('exception_notifications', function (Blueprint $table): void { $table->id(); $table->foreignId('group_id')->constrained('exception_groups'); $table->string('channel_code',64); $table->string('notification_type',64); $table->string('dedupe_key',255)->unique(); $table->string('status',32)->default('pending'); $table->unsignedInteger('attempts')->default(0); $table->dateTime('sent_at',6)->nullable(); $table->string('last_error',2000)->nullable(); $table->json('payload_summary_json')->nullable(); $table->timestamps(6); });
    }
    public function down(): void { Schema::dropIfExists('exception_notifications'); Schema::dropIfExists('exception_dispatches'); Schema::dropIfExists('exception_groups'); Schema::dropIfExists('exception_events'); }
};
