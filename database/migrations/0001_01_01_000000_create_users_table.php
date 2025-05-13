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
        // -- 1. Thêm cột id (nếu chưa có - kiểu bigserial tự tăng)
        // ALTER TABLE users ADD COLUMN id BIGSERIAL PRIMARY KEY;

        // -- 2. Thêm cột name
        // ALTER TABLE users ADD COLUMN name VARCHAR(255) NOT NULL;

        // -- 3. Thêm cột email (unique)
        // ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL;
        // CREATE UNIQUE INDEX users_email_unique ON users (email);

        // -- 4. Thêm cột email_verified_at (nullable timestamp)
        // ALTER TABLE users ADD COLUMN email_verified_at TIMESTAMP NULL;

        // -- 5. Thêm cột password
        // ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL;

        // -- 6. Thêm cột remember_token (nullable string 100)
        // ALTER TABLE users ADD COLUMN remember_token VARCHAR(100);

        // -- 7. Thêm cột created_at và updated_at (timestamp with timezone)
        // ALTER TABLE users ADD COLUMN created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP;
        // ALTER TABLE users ADD COLUMN updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP;

        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        // CREATE TABLE password_reset_tokens (
        //     email VARCHAR PRIMARY KEY,
        //     token VARCHAR NOT NULL,
        //     created_at TIMESTAMP
        // );

        // ALTER TABLE password_reset_tokens ADD COLUMN email VARCHAR PRIMARY KEY;
        // ALTER TABLE password_reset_tokens ADD COLUMN token VARCHAR;
        // ALTER TABLE password_reset_tokens ADD COLUMN created_at TIMESTAMP;

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        // ALTER TABLE sessions ADD COLUMN id VARCHAR PRIMARY KEY;
        // ALTER TABLE sessions ADD COLUMN user_id BIGINT;
        // ALTER TABLE sessions ADD COLUMN ip_address VARCHAR(45);
        // ALTER TABLE sessions ADD COLUMN user_agent TEXT;
        // ALTER TABLE sessions ADD COLUMN payload TEXT NOT NULL;
        // ALTER TABLE sessions ADD COLUMN last_activity INTEGER;

        // // -- Tùy chọn: thêm index
        // CREATE INDEX sessions_user_id_index ON sessions(user_id);
        // CREATE INDEX sessions_last_activity_index ON sessions(last_activity);

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->foreignId('user_id')->nullable()->index();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('users');
        // Schema::dropIfExists('password_reset_tokens');
        // Schema::dropIfExists('sessions');
    }
};
