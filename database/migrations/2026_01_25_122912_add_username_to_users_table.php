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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
        });

        // Backfill for Admin and Kasir
        $users = \Illuminate\Support\Facades\DB::table('users')
            ->whereIn('role', ['admin', 'kasir'])
            ->get();

        foreach ($users as $user) {
            $baseUsername = explode('@', $user->email)[0];
            $username = $baseUsername;
            $counter = 1;

            while (\Illuminate\Support\Facades\DB::table('users')->where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $user->id)
                ->update(['username' => $username]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
