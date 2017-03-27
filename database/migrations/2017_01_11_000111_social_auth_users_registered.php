<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocialAuthUsersRegistered extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_auth_users_registered', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment = 'ID юзера из базы';
            $table->string('social_auth_user_id', 32)->comment = 'ID юзера из соц. сети';
            $table->integer('social_auth_type')->comment = 'Тип авторизации (какая соц. сеть)';
            $table->timestamps();

            // foreign key
            $table->index('social_auth_type');
            
            // users
            $table->foreign('user_id')
                ->references('id')->on('users')
                // если из `users` кто то удален - грохаем его и тут
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // social_auth_users
            $table->foreign('social_auth_type')
                ->references('client_id')->on('social_auth_clients')
                // На данный клиент уже есть юзеры, удаление невожможно
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    
        // Уникальный составной ключ (тип соц. сети и ID соц. сети)
        DB::statement(
            "ALTER TABLE `social_auth_users_registered`
                ADD CONSTRAINT auth_users_registered
                    UNIQUE (`social_auth_user_id`, `social_auth_type`)");

        DB::statement(
            "ALTER TABLE `social_auth_users_registered`
                COMMENT 'Авторизация через соц. сети: зарегистрированные юзеры'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('social_auth_users_registered');
    }
}
