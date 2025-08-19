<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Para PostgreSQL, desabilita triggers temporariamente (evita erros durante a alteração de FKs)
        DB::statement('ALTER TABLE comic_user DISABLE TRIGGER ALL;');

        // Remove FKs antigas
        Schema::table('comic_user', function (Blueprint $table) {
            $table->dropForeign(['comic_id']);  // Nome da FK: comic_user_comic_id_foreign
            $table->dropForeign(['user_id']);   // Nome da FK: comic_user_user_id_foreign
        });

        // Recria as FKs com CASCADE
        Schema::table('comic_user', function (Blueprint $table) {
            // FK para comics
            $table->foreign('comic_id')
                  ->references('id')
                  ->on('comics')
                  ->cascadeOnDelete(); // <--- CASCADE para comic_id

            // FK para users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete(); // <--- CASCADE para user_id
        });

        // Reabilita triggers
        DB::statement('ALTER TABLE comic_user ENABLE TRIGGER ALL;');
    }

    public function down()
    {
        // Reverte para FKs sem CASCADE
        Schema::table('comic_user', function (Blueprint $table) {
            $table->dropForeign(['comic_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('comic_user', function (Blueprint $table) {
            // Recria FKs originais (sem CASCADE)
            $table->foreign('comic_id')
                  ->references('id')
                  ->on('comics');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }
};