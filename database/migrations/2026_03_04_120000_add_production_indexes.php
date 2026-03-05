<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $index): bool
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as c FROM information_schema.statistics 
             WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?",
            [$table, $index]
        );

        return (int) $result->c > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexes = [
            'users_activated_index' => fn () => Schema::table('users', fn (Blueprint $t) => $t->index('activated')),
            'users_activation_digest_index' => fn () => Schema::table('users', fn (Blueprint $t) => $t->index('activation_digest')),
            'users_reset_digest_index' => fn () => Schema::table('users', fn (Blueprint $t) => $t->index('reset_digest')),
            'users_reset_sent_at_index' => fn () => Schema::table('users', fn (Blueprint $t) => $t->index('reset_sent_at')),
            'users_email_reset_digest_index' => fn () => Schema::table('users', fn (Blueprint $t) => $t->index(['email', 'reset_digest'])),
            'microposts_user_id_created_at_index' => fn () => Schema::table('microposts', fn (Blueprint $t) => $t->index(['user_id', 'created_at'])),
        ];

        foreach ($indexes as $name => $callback) {
            $table = str_starts_with($name, 'users_') ? 'users' : 'microposts';
            if (! $this->indexExists($table, $name)) {
                $callback();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $drops = [
            ['users', 'users_activated_index'],
            ['users', 'users_activation_digest_index'],
            ['users', 'users_reset_digest_index'],
            ['users', 'users_reset_sent_at_index'],
            ['users', 'users_email_reset_digest_index'],
            ['microposts', 'microposts_user_id_created_at_index'],
        ];

        foreach ($drops as [$table, $index]) {
            if ($this->indexExists($table, $index)) {
                Schema::table($table, fn (Blueprint $t) => $t->dropIndex($index));
            }
        }
    }
};
