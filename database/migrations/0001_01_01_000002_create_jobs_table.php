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
        // CREATE TABLE jobs (
        //     id SERIAL PRIMARY KEY,
        //     queue VARCHAR NOT NULL,
        //     payload TEXT NOT NULL,
        //     attempts SMALLINT NOT NULL,
        //     reserved_at INTEGER,
        //     available_at INTEGER NOT NULL,
        //     created_at INTEGER NOT NULL
        // );
        // CREATE INDEX jobs_queue_index ON jobs(queue);
        // Schema::create('jobs', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('queue')->index();
        //     $table->longText('payload');
        //     $table->unsignedTinyInteger('attempts');
        //     $table->unsignedInteger('reserved_at')->nullable();
        //     $table->unsignedInteger('available_at');
        //     $table->unsignedInteger('created_at');
        // });

        // ALTER TABLE jobs ADD COLUMN queue VARCHAR NOT NULL;
        // CREATE INDEX jobs_queue_index ON jobs(queue);

        // ALTER TABLE jobs ADD COLUMN payload TEXT NOT NULL;

        // ALTER TABLE jobs ADD COLUMN attempts SMALLINT NOT NULL;

        // ALTER TABLE jobs ADD COLUMN reserved_at INTEGER;

        // ALTER TABLE jobs ADD COLUMN available_at INTEGER NOT NULL;

        // ALTER TABLE jobs ADD COLUMN created_at INTEGER NOT NULL;

        // -----------------

        // CREATE TABLE job_batches (
        //     id VARCHAR PRIMARY KEY,
        //     name VARCHAR NOT NULL,
        //     total_jobs INTEGER NOT NULL,
        //     pending_jobs INTEGER NOT NULL,
        //     failed_jobs INTEGER NOT NULL,
        //     failed_job_ids TEXT NOT NULL,
        //     options TEXT,
        //     cancelled_at INTEGER,
        //     created_at INTEGER NOT NULL,
        //     finished_at INTEGER
        // );

        // Schema::create('job_batches', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->string('name');
        //     $table->integer('total_jobs');
        //     $table->integer('pending_jobs');
        //     $table->integer('failed_jobs');
        //     $table->longText('failed_job_ids');
        //     $table->mediumText('options')->nullable();
        //     $table->integer('cancelled_at')->nullable();
        //     $table->integer('created_at');
        //     $table->integer('finished_at')->nullable();
        // });

        // ALTER TABLE job_batches ADD COLUMN name VARCHAR NOT NULL;

        // ALTER TABLE job_batches ADD COLUMN total_jobs INTEGER NOT NULL;

        // ALTER TABLE job_batches ADD COLUMN pending_jobs INTEGER NOT NULL;

        // ALTER TABLE job_batches ADD COLUMN failed_jobs INTEGER NOT NULL;

        // ALTER TABLE job_batches ADD COLUMN failed_job_ids TEXT NOT NULL;

        // ALTER TABLE job_batches ADD COLUMN options TEXT;

        // ALTER TABLE job_batches ADD COLUMN cancelled_at INTEGER;

        // ALTER TABLE job_batches ADD COLUMN created_at INTEGER NOT NULL;

        // ALTER TABLE job_batches ADD COLUMN finished_at INTEGER;

        // -----------------

        // ALTER TABLE job_batches ADD COLUMN id VARCHAR PRIMARY KEY;

        // -----------------

        // CREATE TABLE failed_jobs (
        //     id SERIAL PRIMARY KEY,
        //     uuid VARCHAR UNIQUE NOT NULL,
        //     connection TEXT NOT NULL,
        //     queue TEXT NOT NULL,
        //     payload TEXT NOT NULL,
        //     exception TEXT NOT NULL,
        //     failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // );

        // Schema::create('failed_jobs', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('uuid')->unique();
        //     $table->text('connection');
        //     $table->text('queue');
        //     $table->longText('payload');
        //     $table->longText('exception');
        //     $table->timestamp('failed_at')->useCurrent();
        // });

        // ALTER TABLE failed_jobs ADD COLUMN uuid VARCHAR UNIQUE NOT NULL;

        // ALTER TABLE failed_jobs ADD COLUMN connection TEXT NOT NULL;

        // ALTER TABLE failed_jobs ADD COLUMN queue TEXT NOT NULL;

        // ALTER TABLE failed_jobs ADD COLUMN payload TEXT NOT NULL;

        // ALTER TABLE failed_jobs ADD COLUMN exception TEXT NOT NULL;

        // ALTER TABLE failed_jobs ADD COLUMN failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('jobs');
        // Schema::dropIfExists('job_batches');
        // Schema::dropIfExists('failed_jobs');
    }
};
