<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Move client records from users table to clients table, then remove from users.
     */
    public function up(): void
    {
        $clients = DB::table('users')->where('user_type', 'client')->get();
        foreach ($clients as $row) {
            DB::table('clients')->insert([
                'id' => $row->id,
                'admin_id' => $row->admin_id,
                'name' => $row->name ?? $row->email,
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'email' => $row->email,
                'password' => $row->password,
                'is_active' => (bool) ($row->is_active ?? true),
                'email_verified_at' => $row->email_verified_at,
                'email_verification_token' => $row->email_verification_token ?? null,
                'remember_token' => $row->remember_token,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
        DB::table('users')->where('user_type', 'client')->delete();
    }

    /**
     * Reverse: copy back from clients to users (with user_type = 'client').
     */
    public function down(): void
    {
        $clients = DB::table('clients')->get();
        foreach ($clients as $row) {
            DB::table('users')->insert([
                'id' => $row->id,
                'admin_id' => $row->admin_id,
                'name' => $row->name,
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'email' => $row->email,
                'password' => $row->password,
                'user_type' => 'client',
                'is_active' => $row->is_active,
                'email_verified_at' => $row->email_verified_at,
                'email_verification_token' => $row->email_verification_token,
                'remember_token' => $row->remember_token,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
        DB::table('clients')->truncate();
    }
};
