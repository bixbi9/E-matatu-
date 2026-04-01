<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'user_uid')) {
                $table->uuid('user_uid')->nullable()->unique();
            }

            if (! Schema::hasColumn('users', 'supabase_user_id')) {
                $table->uuid('supabase_user_id')->nullable()->unique();
            }

            if (! Schema::hasColumn('users', 'supabase_synced_at')) {
                $table->timestamp('supabase_synced_at')->nullable();
            }
        });

        $users = DB::table('users')->select('id', 'user-id', 'user_uid')->get();

        foreach ($users as $user) {
            $legacyUserId = data_get($user, 'user-id');
            $userUid = $user->user_uid ?: ($legacyUserId ?: (string) Str::uuid());

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'user-id' => $legacyUserId ?: $userUid,
                    'user_uid' => $userUid,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'supabase_synced_at')) {
                $table->dropColumn('supabase_synced_at');
            }

            if (Schema::hasColumn('users', 'supabase_user_id')) {
                $table->dropUnique(['supabase_user_id']);
                $table->dropColumn('supabase_user_id');
            }

            if (Schema::hasColumn('users', 'user_uid')) {
                $table->dropUnique(['user_uid']);
                $table->dropColumn('user_uid');
            }
        });
    }
};
