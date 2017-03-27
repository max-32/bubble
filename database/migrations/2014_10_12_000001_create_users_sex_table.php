<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersSexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_sex', function (Blueprint $table) {
            $table->string('id')->comment = 'ID для пола пользователя';
            $table->string('description')->comment = 'Описание пола пользователя';

            // foreign
            $table->index('id');
        });

        DB::statement("ALTER TABLE `users_sex` comment 'Виды доступных `полов` пользователей'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_sex');
    }
}
