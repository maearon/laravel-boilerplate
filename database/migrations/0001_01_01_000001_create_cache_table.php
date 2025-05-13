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
        // CREATE TABLE cache (
        //     key TEXT PRIMARY KEY,
        //     value TEXT NOT NULL,
        //     expiration INTEGER NOT NULL
        // );

        // -- Thêm cột 'key' (nếu chưa có) và đặt làm PRIMARY KEY
        // ALTER TABLE cache ADD COLUMN key TEXT;
        // ALTER TABLE cache ADD PRIMARY KEY (key);

        // -- Thêm cột 'value' kiểu mediumtext → dùng TEXT trong PostgreSQL
        // ALTER TABLE cache ADD COLUMN value TEXT;

        // -- Thêm cột 'expiration' kiểu integer
        // ALTER TABLE cache ADD COLUMN expiration INTEGER;

        // Schema::create('cache', function (Blueprint $table) {
        //     $table->string('key')->primary();
        //     $table->mediumText('value');
        //     $table->integer('expiration');
        // });

        // CREATE TABLE cache_locks (
        //     key TEXT PRIMARY KEY,
        //     owner TEXT NOT NULL,
        //     expiration INTEGER NOT NULL
        // );

        // -- Thêm cột 'key' và đặt làm PRIMARY KEY
        // ALTER TABLE cache_locks ADD COLUMN key TEXT;
        // ALTER TABLE cache_locks ADD PRIMARY KEY (key);

        // -- Thêm cột 'owner' kiểu string → TEXT trong PostgreSQL
        // ALTER TABLE cache_locks ADD COLUMN owner TEXT;

        // -- Thêm cột 'expiration'
        // ALTER TABLE cache_locks ADD COLUMN expiration INTEGER;

        // Schema::create('cache_locks', function (Blueprint $table) {
        //     $table->string('key')->primary();
        //     $table->string('owner');
        //     $table->integer('expiration');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('cache');
        // Schema::dropIfExists('cache_locks');
    }
};
