<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('user_path')->nullable()->after('id');
            $table->string('verified')->default(false);
            $table->text('description')->nullable();
        });
    
        // Gerar UUIDs únicos para todos os usuários existentes
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['user_path' => Str::uuid()]);
        }
    
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('user_path')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_path');
            $table->dropColumn('verified');
            $table->dropColumn('description');
        });
    }
};
